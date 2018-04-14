<?php

namespace Snowtricks\PlatformBundle\Service;

use Snowtricks\PlatformBundle\Entity\User;

/*
 * Mail Sender
 */
class MailSender
{
	protected $twig;
	protected $mailer;

	public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
	{
		$this->twig = $twig;
		$this->mailer = $mailer;
	}

	public function sendMail($template, $object, User $user)
	{
		$message = (new \Swift_Message($object))
            ->setFrom('manue21x@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render('emails/registration.html.twig', array(
                    'name'  => $user->getUsername(),
                    'token' => $user->getToken(),
                    'userId' => $user->getId())),
                'text/html'
            );
        $this->mailer->send($message);
	}
}