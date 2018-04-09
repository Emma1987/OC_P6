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

		// PAGINATION
		$perPage = $this->container->getParameter('message.pagination');
		$page = $request->query->get('page', 1);

		$pagination = $this
			->getDoctrine()
			->getManager()
			->getRepository('SnowtricksPlatformBundle:Message')
			->paginator($trick->getId(), $page, $perPage);

		$nbPages = ceil(count($pagination) / $perPage);

		// VIEW
		return $this->render('tricks/view.html.twig', array(
			'trick' => $trick,
			'images' => $trick->getImages(),
			'videos' => $trick->getVideos(),
			'messages' => $pagination,
			'page' => $page,
			'nbPages' => $nbPages,
			'messageForm' => $messageForm->createView(),
			
		));
	}

	public function addAction(Request $request)
	{
		$trick = new Trick();
		$trickForm = $this->createForm(TrickType::class, $trick);

		$trickForm->handleRequest($request);

		if ($trickForm->isSubmitted() && $trickForm->isValid()) {
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

		return $this->render('tricks/add.html.twig', array(
			'trickForm' => $trickForm->createView()
		));
	}

	public function updateAction(Request $request)
	{
		$trick = $this->getDoctrine()
			->getManager()
			->getRepository('SnowtricksPlatformBundle:Trick')
			->findOneBySlug($request->attributes->get('slug'));

		$trickForm = $this->createForm(TrickType::class, $trick);

		$trickForm->handleRequest($request);

		if ($trickForm->isSubmitted() && $trickForm->isValid()) {
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

			$request->getSession()->getFlashBag()->add('notice', 'Votre figure a bien été modifiée !');
			return $this->redirectToRoute('snowtricks_view', array(
				'slug' => $trick->getSlug()
			));
		}

		return $this->render('tricks/update.html.twig', array(
			'trickForm' => $trickForm->createView(),
			'trick'		=> $trick,
			'images'	=> $trick->getImages(),
			'videos'	=> $trick->getVideos()
		));
	}

	public function deleteAction(Request $request)
	{
		$trick = $this
			->getDoctrine()
			->getManager()
			->getRepository('SnowtricksPlatformBundle:Trick')
			->findOneById($request->attributes->get('id'));

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($trick);
		$entityManager->flush();

		$request->getSession()->getFlashBag()->add('notice', 'La figure a bien été supprimée.');
		return $this->redirectToRoute('snowtricks_home');
	}
}