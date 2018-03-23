<?php
namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Snowtricks\PlatformBundle\Entity\Trick;
use Snowtricks\PlatformBundle\Entity\Image;
use Snowtricks\PlatformBundle\Entity\Message;
use Snowtricks\PlatformBundle\Form\TrickType;
use Snowtricks\PlatformBundle\Form\ImageType;
use Snowtricks\PlatformBundle\Form\MessageType;

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

		// MESSAGE FORM
		$message = new Message();
		$messageForm = $this->createForm(MessageType::class, $message);

		if ($request->isMethod('POST')) {
			$messageForm->handleRequest($request);

			if ($messageForm->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($message);
				$trick->addMessage($message);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Votre figure a bien été ajoutée !');
				return $this->redirectToRoute('snowtricks_view', array(
					'slug' => $trick->getSlug()
				));
			}
		}

		// VIEW
		return $this->render('view.html.twig', array(
			'trick' => $trick,
			'images' => $trick->getImages(),
			'videos' => $trick->getVideos(),
			'messages' => $trick->getMessages(),
			'messageForm' => $messageForm->createView(),
			
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