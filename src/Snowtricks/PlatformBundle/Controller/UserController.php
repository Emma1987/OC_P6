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
	public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
	{
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('login.html.twig', array(
			'last_username'	=> $lastUsername,
			'error'			=> $error
		));
	}
}