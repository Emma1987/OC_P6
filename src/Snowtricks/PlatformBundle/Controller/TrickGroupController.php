<?php
namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Snowtricks\PlatformBundle\Entity\Trick;
use Snowtricks\PlatformBundle\Entity\TrickGroup;
use Snowtricks\PlatformBundle\Form\TrickType;
use Snowtricks\PlatformBundle\Form\TrickGroupType;

class TrickGroupController extends Controller
{
	public function addAction(Request $request)
	{
		$trickGroup = new TrickGroup();
		$groupForm = $this->createForm(TrickGroupType::class, $trickGroup);

		if ($request->isMethod('POST')) {
			$groupForm->handleRequest($request);

			if ($groupForm->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($trickGroup);
				$em->flush();
			}
		}

		return $this->render('addGroup.html.twig', array(
			'groupForm' => $groupForm->createView()
		));
	}
}