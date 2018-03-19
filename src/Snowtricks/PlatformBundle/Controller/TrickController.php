<?php
namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Snowtricks\PlatformBundle\Entity\Trick;
use Snowtricks\PlatformBundle\Entity\Image;
use Snowtricks\PlatformBundle\Form\TrickType;
use Snowtricks\PlatformBundle\Form\ImageType;

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
			'trick' => $trick,
			'images' => $trick->getImages()
		));
	}

	public function addAction(Request $request)
	{
		$trick = new Trick();
		$trickForm = $this->createForm(TrickType::class, $trick);

		if ($request->isMethod('POST')) {
			$trickForm->handleRequest($request);

			if ($trickForm->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($trick);
				$slug = $trick->createSlug($trick->getName());
				$trick->setSlug($slug);

				if (!empty($trickForm['images']->getData())) {
					$files = $trickForm['images']->getData();
					foreach ($files as $file) {
						$image = new Image();
						$trick->addImage($image);
						$image->upload($file);
					}
				}
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Votre figure a bien été ajoutée !');
				return $this->redirectToRoute('snowtricks_view', array(
					'slug' => $trick->getSlug()
				));
			}
		}

		return $this->render('add.html.twig', array(
			'trickForm' => $trickForm->createView()
		));
	}
}