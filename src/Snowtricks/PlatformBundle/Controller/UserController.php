<?php

namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Snowtricks\PlatformBundle\Entity\User;
use Snowtricks\PlatformBundle\Entity\Avatar;
use Snowtricks\PlatformBundle\Form\UserType;
use Snowtricks\PlatformBundle\Form\AvatarType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Snowtricks\PlatformBundle\Service\TokenGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    /**
     * Register a new user and send an email to get a confirmation
     * @param  Request                      $request
     * @param  UserPasswordEncoderInterface $passwordEncoder
     * @param  \Swift_Mailer                $mailer
     * @param  TokenGenerator               $tokenGen        [Service used to generate a token]
     *
     * @Route(
     *     "/user/register", 
     *     name="snowtricks_register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, TokenGenerator $tokenGen)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $token = $tokenGen->generateToken(60);
            $user->setToken($token);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Vous n\'êtes plus qu\'à un clic de notre communauté !'))
                ->setFrom('email@example.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView('emails/registration.html.twig', array(
                        'name'  => $user->getUsername(),
                        'token' => $user->getToken(),
                        'userId' => $user->getId())),
                    'text/html'
                );
            $mailer->send($message);

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
        $userRepo = $this->getDoctrine()->getManager()->getRepository(User::class);
        $user = $userRepo->findOneById($request->attributes->get('userId'));

        if ($user == null || $user->getToken() != $request->attributes->get('token')) {
            $request->getSession()->getFlashBag()->add('warning', 'Ce mail de confirmation n\'est associé à aucune demande de création de compte.');
            return $this->render('users/register.html.twig', array(
                'userForm' => $this->createForm(UserType::class, $user)->createView()));
        } else {
            $user->setToken(null);
            $user->setIsActive(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush($user);

            $request->getSession()->getFlashBag()->add('success', 'Votre inscription à bien été validée.');
            return $this->redirectToRoute('snowtricks_login');
        }
    }

    /**
     * Send a mail to reset the password
     * @param  Request        $request
     * @param  \Swift_Mailer  $mailer
     * @param  TokenGenerator $tokenGen [Service used to generate a token]
     *
     * @Route(
     *     "/user/reset-password", 
     *     name="snowtricks_resetpass")
     */
    public function resetPassAction(Request $request, \Swift_Mailer $mailer, TokenGenerator $tokenGen)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user, array(
            'validation_groups' => array('resetPassDemand')));
        $userForm->remove('email');
        $userForm->remove('plainPassword');

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByUsername($userForm->getData()->getUsername());
            if ($user == null) {
                $request->getSession()->getFlashBag()->add('warning', 'Ce nom dutilisateur ne me dit rien...');
            } else {
                $token = $tokenGen->generateToken(60);
                $user->setToken($token);

                $entityManager->flush($user);

                $message = (new \Swift_Message('Votre demande de réinitialisation de mot de passe'))
                    ->setFrom('manue21x@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView('emails/reset_pass.html.twig', array(
                            'name'  => $user->getUsername(),
                            'token' => $user->getToken(),
                            'userId' => $user->getId())),
                        'text/html'
                );
                $mailer->send($message);
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
        $userRepo = $this->getDoctrine()->getManager()->getRepository(User::class);
        $user = $userRepo->findOneById($request->attributes->get('userId'));

        if ($user == null || $user->getToken() != $request->attributes->get('token')) {
            $request->getSession()->getFlashBag()->add('warning', 'Ce mail de confirmation n\'est pas valide.');
            
            $userForm = $this->createForm(UserType::class, $user, array(
                'validation_groups' => array('resetPassDemand')));
            $userForm->remove('email');
            $userForm->remove('plainPassword');

            return $this->redirectToRoute('snowtricks_resetpass', array(
                'userForm' => $userForm->createView()));
        } else {
            $userForm = $this->createForm(UserType::class, $user, array(
                'validation_groups' => array('resetPassAction')));
            $userForm->remove('username');

            $userForm->handleRequest($request);
            if ($userForm->isSubmitted() && $userForm->isValid()) {
                $user->setToken(null);
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush($user);

                $request->getSession()->getFlashBag()->add('success', 'Votre mot de passe a bien été modifié !');
                return $this->redirectToRoute('snowtricks_login');
            }
            return $this->render('users/reset_pass_action.html.twig', array(
                'userForm' => $userForm->createView()));
        }
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush($user);
        }

        return $this->render('users/account.html.twig', array(
            'avatarForm' => $avatarForm->createView(),
            'avatar'     => $this->getUser()->getAvatar()
        ));
    }
}