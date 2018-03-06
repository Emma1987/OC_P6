<?php
namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends Controller
{
	public function indexAction()
	{
		$listTricks = $this
			->getDoctrine()
			->getManager()
			->getRepository('SnowtricksPlatformBundle:Trick')
			->findAll();

		return $this->render('index.html.twig', array(
			'listTricks' => $listTricks
		));
	}
}