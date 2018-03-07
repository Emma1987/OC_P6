<?php
namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Snowtricks\PlatformBundle\Entity\Trick;
use Snowtricks\PlatformBundle\Form\TrickType;

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

	public function addAction(Request $request)
	{
		$trick = new Trick();
		$form = $this->get('form.factory')->create(TrickType::class, $trick);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$trick->setSlug('Test');
				$em->persist($trick);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Votre figure a bien été ajoutée !');
				return $this->redirectToRoute('snowtricks_view', array(
					'slug' => $trick->getSlug()
				));
			}
		}

		return $this->render('add.html.twig', array(
			'form' => $form->createView()
		));
	}
}