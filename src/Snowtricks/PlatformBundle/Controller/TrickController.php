<?php

namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Snowtricks\PlatformBundle\Entity\Trick;
use Snowtricks\PlatformBundle\Entity\Image;
use Snowtricks\PlatformBundle\Entity\Message;
use Snowtricks\PlatformBundle\Form\TrickType;
use Snowtricks\PlatformBundle\Form\ImageType;
use Snowtricks\PlatformBundle\Form\MessageType;
use Snowtricks\PlatformBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TrickController extends Controller
{
    /**
     * Return homepage view
     *
     * @Route(
     *     "/", 
     *     name="snowtricks_home")
     */
    public function indexAction()
    {
        $trickRepo = $this->getDoctrine()->getManager()->getRepository(Trick::class);
        $listTricks = $trickRepo->findAll();

        return $this->render('tricks/index.html.twig', array(
            'listTricks' => $listTricks,
        ));
    }

    /**
     * Return all tricks view and search form
     * @param  Request $request
     *
     * @Route(
     *     "/trick/all", 
     *     name="snowtricks_all_tricks")
     */
    public function allTricksAction(Request $request)
    {
        $trickRepo = $this->getDoctrine()->getManager()->getRepository(Trick::class);
        $searchForm = $this->createForm(SearchType::class);

        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $trickName = htmlspecialchars($searchForm->getData()['trickName']);
            $group = $searchForm->getData()['groupName'];
            $listTricks = $trickRepo->search($trickName, $group);
        } else {
            $listTricks = $trickRepo->findAll();
        }

        return $this->render('tricks/all_tricks.html.twig', array(
            'listTricks' => $listTricks,
            'searchForm' => $searchForm->createView()
        ));
    }

    /**
     * View a single trick with its messages, and display form to post message
     * @param  Request $request
     *
     * @Route(
     *     "/tricks/{slug}", 
     *     name="snowtricks_view", 
     *     requirements={"slug"="[a-zA-Z0-9-]+"})
     */
    public function showAction(Request $request)
    {
        $trickRepo = $this->getDoctrine()->getManager()->getRepository(Trick::class);
        $trick = $trickRepo->findOneBySlug($request->attributes->get('slug'));

        // MESSAGE FORM
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);
        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $message->setUser($this->getUser());
            $trick->addMessage($message);
            $entityManager->flush();

            $request->getSession()->getFlashBag()->add('success', 'Votre message a bien été ajouté !');
            return $this->redirectToRoute('snowtricks_view', array(
                'slug' => $trick->getSlug()
            ));
        }

        // PAGINATION
        $perPage = Message::NUMBER_PAGINATION;
        $page = $request->query->get('page', 1);

        $messageRepo = $this->getDoctrine()->getManager()->getRepository(Message::class);
        $pagination = $messageRepo->paginator($trick->getId(), $page, $perPage);

        $nbPages = ceil(count($pagination) / $perPage);

        // VIEW
        return $this->render('tricks/view.html.twig', array(
            'trick'       => $trick,
            'images'      => $trick->getImages(),
            'videos'      => $trick->getVideos(),
            'messages'    => $pagination,
            'page'        => $page,
            'nbPages'     => $nbPages,
            'messageForm' => $messageForm->createView(),
        ));
    }

    /**
     * Add a new trick
     * @param Request $request
     *
     * @Route(
     *     "/trick/add", 
     *     name="snowtricks_add")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addAction(Request $request)
    {
        $trick = new Trick();
        $trickForm = $this->createForm(TrickType::class, $trick);

        $trickForm->handleRequest($request);
        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
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
            $entityManager->flush();

            $request->getSession()->getFlashBag()->add('success', 'Votre figure a bien été ajoutée !');
            return $this->redirectToRoute('snowtricks_view', array(
                'slug' => $trick->getSlug()
            ));
        }

        return $this->render('tricks/add.html.twig', array(
            'trickForm' => $trickForm->createView()
        ));
    }

    /**
     * Update a trick
     * @param  Request $request
     *
     * @Route(
     *     "/trick/update/{slug}", 
     *     name="snowtricks_update", 
     *     requirements={"slug"="[a-zA-Z0-9-]+"})
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function updateAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trick = $entityManager->getRepository(Trick::class)->findOneBySlug($request->attributes->get('slug'));

        $trickForm = $this->createForm(TrickType::class, $trick);

        $trickForm->handleRequest($request);
        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
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
            $entityManager->flush();

            $request->getSession()->getFlashBag()->add('success', 'Votre figure a bien été modifiée !');
            return $this->redirectToRoute('snowtricks_view', array(
                'slug' => $trick->getSlug()
            ));
        }

        return $this->render('tricks/update.html.twig', array(
            'trickForm' => $trickForm->createView(),
            'trick'     => $trick,
            'images'    => $trick->getImages(),
            'videos'    => $trick->getVideos()
        ));
    }

    /**
     * Delete a trick
     * @param  Request $request
     *
     * @Route(
     *     "/trick/delete/{id}", 
     *     name="snowtricks_delete", 
     *     requirements={"id"="\d+"})
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trick = $entityManager->getRepository(Trick::class)->findOneById($request->attributes->get('id'));

        $entityManager->remove($trick);
        $entityManager->flush();

        $request->getSession()->getFlashBag()->add('success', 'La figure a bien été supprimée.');
        return $this->redirectToRoute('snowtricks_all_tricks');
    }
}