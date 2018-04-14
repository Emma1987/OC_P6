<?php

namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Snowtricks\PlatformBundle\Entity\User;
use Snowtricks\PlatformBundle\Entity\Avatar;
use Snowtricks\PlatformBundle\Form\UserType;
use Snowtricks\PlatformBundle\Form\AvatarType;

class UserController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Register a new user and send an email to get a confirmation
     * @param  Request                      $request
     * @param  UserPasswordEncoderInterface $passwordEncoder
     *
     * @Route(
     *     "/user/register", 
     *     name="snowtricks_register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user, array(
            'validation_groups' => array('register')));

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $token = $this->get('app.token_generator')->generateToken(60);
            $user->setToken($token);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->get('app.mail_sender')->sendMail('emails/registration.html.twig', 'Vous n\'êtes plus qu\'à un clic de notre communauté !', $user);

            $request->getSession()->getFlashBag()->add('success', 'Un mail de confirmation vous a été envoyé');
        }

        return $this->render('users/register.html.twig', array(
            'userForm' => $userForm->createView()));
    }

    /**
     * Get the url in the email to activate the account
     * @param  Request $request
     *
     * @Route(
     *     "/user/confirm-register/{userId}-{token}", 
     *     name="snowtricks_confirm_register")
     */
    public function confirmRegisterAction(Request $request)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneById($request->attributes->get('userId'));

        if ($user == null || $user->getToken() != $request->attributes->get('token')) {
            $request->getSession()->getFlashBag()->add('warning', 'Ce mail de confirmation n\'est associé à aucun compte.');
            return $this->redirectToRoute('snowtricks_register');
        }

        $user->setToken(null);
        $user->setIsActive(true);

        $this->entityManager->flush($user);

        $request->getSession()->getFlashBag()->add('success', 'Votre inscription à bien été validée.');
        return $this->redirectToRoute('snowtricks_login');
    }

    /**
     * Send a mail to reset the password
     * @param  Request        $request
     *
     * @Route(
     *     "/user/reset-password", 
     *     name="snowtricks_resetpass")
     */
    public function resetPassAction(Request $request)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user, array(
            'validation_groups' => array('resetPassDemand')));
        $userForm->remove('email');
        $userForm->remove('plainPassword');

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user = $this->entityManager->getRepository(User::class)->findOneByUsername($userForm->getData()->getUsername());
            if ($user == null) {
                $request->getSession()->getFlashBag()->add('warning', 'Ce nom dutilisateur ne me dit rien...');
            } else {
                $token = $this->get('app.token_generator')->generateToken(60);
                $user->setToken($token);

                $this->entityManager->flush($user);

                $this->get('app.mail_sender')->sendMail('emails/reset_pass.html.twig', 'Votre demande de réinitialisation de mot de passe', $user);

                $request->getSession()->getFlashBag()->add('success', 'Un mail vous a été envoyé !');
            }
        }
        return $this->render('users/reset_pass_demand.html.twig', array(
            'userForm' => $userForm->createView()));
    }

    /**
     * Get the url in the email to update the password
     * @param  Request                      $request
     * @param  UserPasswordEncoderInterface $passwordEncoder
     *
     * @Route(
     *     "/user/confirm-reset-password/{userId}-{token}", 
     *     name="snowtricks_confirm_resetpass")
     */
    public function confirmResetPassAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneById($request->attributes->get('userId'));
        $email = $user->getEmail();

        $userForm = $this->createForm(UserType::class, $user, array(
            'validation_groups' => array('resetPassAction')));
        $userForm->remove('username');

        if ($user == null || $user->getToken() == null || $user->getToken() != $request->attributes->get('token')) {
            $request->getSession()->getFlashBag()->add('warning', 'Ce mail de réinitialisation n\'est pas valide.');
            return $this->redirectToRoute('snowtricks_login');
        }

        $userForm->handleRequest($request);

        if ($user->getEmail() != $email) {
            $request->getSession()->getFlashBag()->add('warning', 'Je ne reconnais pas cet email...');
            return $this->render('users/reset_pass_action.html.twig', array(
                'userForm' => $userForm->createView()));
        }

        if ($userForm->isSubmitted() && $userForm->isValid() && $user->getToken() == $request->attributes->get('token')) {
            $user->setToken(null);
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $this->entityManager->flush($user);

            $request->getSession()->getFlashBag()->add('success', 'Votre mot de passe a bien été modifié !');
            return $this->redirectToRoute('snowtricks_login');
        }

        return $this->render('users/reset_pass_action.html.twig', array(
            'userForm' => $userForm->createView()));
    }

    /**
     * Login action
     * @param  Request             $request
     * @param  AuthenticationUtils $authenticationUtils
     *
     * @Route(
     *     "/login", 
     *     name="snowtricks_login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('users/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error
        ));
    }

    /**
     * Logout action
     *
     * @Route(
     *     "/logout", 
     *     name="snowtricks_logout")
     */
    public function logoutAction()
    {
    }

    /**
     * View user account
     *
     * @Route(
     *     "/user/account", 
     *     name="snowtricks_account")
     *
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function accountAction(Request $request)
    {
        $avatar = new Avatar();
        $avatarForm = $this->createForm(AvatarType::class, $avatar);

        $avatarForm->handleRequest($request);
        if ($avatarForm->isSubmitted()) {
            $avatar = new Avatar();
            $file = $avatarForm['file']->getData();
            $name = $this->getUser()->getUsername();
            $avatar->upload($file, $name);
            $user = $this->getUser();
            $user->setAvatar($avatar);

            $this->entityManager->flush($user);
        }

        return $this->render('users/account.html.twig', array(
            'avatarForm' => $avatarForm->createView(),
            'avatar'     => $this->getUser()->getAvatar()
        ));
    }

    /**
     * Delete user avatar
     *
     * @Route("/user/delete-avatar-{id}", name="snowtricks_delete_avatar")
     *
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function deleteAvatarAction(Request $request)
    {
        $avatar = $this->entityManager->getRepository(Avatar::class)->findOneById($request->attributes->get('id'));

        $this->getUser()->setAvatar(null);
        $this->entityManager->flush();

        $this->entityManager->remove($avatar);
        $this->entityManager->flush();

        return $this->redirectToRoute('snowtricks_account');
    }
}