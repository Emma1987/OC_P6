<?php
namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends Controller
{
	public function removeAction(Request $request)
	{
		$image = $this
			->getDoctrine()
			->getManager()
			->getRepository('SnowtricksPlatformBundle:Image')
			->findOneById($request->attributes->get('id'));

		$trick = $image->getTrick();

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($image);
		$entityManager->flush();

		$request->getSession()->getFlashBag()->add('notice', 'L\'image a bien été supprimée.');
		return $this->redirectToRoute('snowtricks_update', array(
			'slug' => $trick->getSlug()
		));
	}
}