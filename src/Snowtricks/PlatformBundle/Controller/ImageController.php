<?php
namespace Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Snowtricks\PlatformBundle\Entity\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ImageController extends Controller
{
	/**
	 * Remove an image when updating trick
	 * @param  Request $request
	 *
	 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
	 */
	public function removeAction(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$image = $entityManager->getRepository(Image::class)->findOneById($request->attributes->get('id'));

		$trick = $image->getTrick();

		$entityManager->remove($image);
		$entityManager->flush();

		$request->getSession()->getFlashBag()->add('notice', 'L\'image a bien été supprimée.');
		return $this->redirectToRoute('snowtricks_update', array(
			'slug' => $trick->getSlug()
		));
	}
}