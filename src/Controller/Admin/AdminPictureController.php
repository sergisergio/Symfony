<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
//use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Picture;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/picture")
 */
class AdminPictureController extends AbstractController
{
    /**
     * @Route("/{id}", name="admin.picture.delete", methods="DELETE")
     * @param  Picture $picture
     * @param  Request $request
     */
    public function delete(Picture $picture, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        //$propertyId = $picture->getProperty()->getId();
        if($this->isCsrfTokenValid('delete' . $picture->getId(), $data['_token'])) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($picture);
            $em->flush();
            return new JsonResponse(['success' => 1]);
            //$this->addFlash('success', 'Image supprimée avec succès');
        } else {
            return new JsonResponse(['error' => 'Token invalide'], 400);
        }
    }
}
