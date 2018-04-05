<?php
namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Snowtricks\PlatformBundle\Entity\User;
use Snowtricks\PlatformBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Snowtricks\PlatformBundle\Service\TokenGenerator;

class UserController extends Controller
{
	public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
	{
		$user = new User();
		$userForm = $this->createForm(UserType::class, $user);

		$userForm->handleRequest($request);
		if ($userForm->isSubmitted() && $userForm->isValid()) {

			$password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($password);

			$token = $this->createToken(60);
			$user->setToken($token);

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();

			$message = (new \Swift_Message('Vous n\'êtes plus qu\'à un clic de notre communauté !'))
				->setFrom('email@example.com')
				->setTo($user->getEmail())
				->setBody(
					$this->renderView('Emails/registration.html.twig', array(
						'name'  => $user->getUsername(),
						'token' => $user->getToken(),
						'userId' => $user->getId())),
					'text/html'
				);
			$mailer->send($message);

			$request->getSession()->getFlashBag()->add('notice', 'Un mail de confirmation vous a été envoyé');
		}

		return $this->render('register.html.twig', array(
			'userForm' => $userForm->createView()));
	}

	public function confirmRegisterAction(Request $request)
	{
		$user = $this
			->getDoctrine()
			->getManager()
			->getRepository('SnowtricksPlatformBundle:User')
			->findOneById($request->attributes->get('userId'));

		if ($user == null || $user->getToken() != $request->attributes->get('token')) {
			$request->getSession()->getFlashBag()->add('warning', 'Ce mail de confirmation n\'est associé à aucune demande de création de compte.');
			return $this->render('register.html.twig', array(
				'userForm' => $this->createForm(UserType::class, $user)->createView()));
		} else {
            $user->setToken(null);
            $user->setIsActive(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush($user);

            $request->getSession()->getFlashBag()->add('success', 'Votre inscription à bien été validée.');
            return $this->render('login.html.twig');
        }
	}

	public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
	{
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('login.html.twig', array(
			'last_username'	=> $lastUsername,
			'error'			=> $error
		));
	}

	public function createToken($length)
	{
		$tokenGenerator = $this->get(TokenGenerator::class);
		$token = $tokenGenerator->generateToken($length);

		return $token;
	}
}