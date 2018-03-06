<?php
namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

	public function showAction(Request $request)
	{
		$trick = $this
			->getDoctrine()
			->getManager()
			->getRepository('SnowtricksPlatformBundle:Trick')
			->findOneBySlug($request->attributes->get('slug'));

		return $this->render('view.html.twig', array(
			'trick' => $trick
		));
	}
}