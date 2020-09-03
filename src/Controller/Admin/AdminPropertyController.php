<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Property;
use App\Entity\Option;
use App\Form\PropertyType;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
//use Liip\ImagineBundle\Imagine\Cache\CacheManager;
//use Vich\UploaderBundle\Templating\Helper\UploaderHelper;


class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     */
    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */
    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
          $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien ajouté avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form'     => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin.property.edit", methods="GET|POST")
     * @param  Property $property
     * @param  Request $request
     */
    public function edit(Property $property, Request $request/*, CacheManager $cacheManager, UploaderHelper $helper*/)
    {
        // $option = new Option();
        // $property->addOption($option);

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // if ($property->getImageFile() instanceof UploadedFile) {
            //     $cacheManager->remove($helper->asset($property, 'imageFile'));
            // }

            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form'     => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin.property.delete", methods="DELETE")
     * @param  Property $property
     * @param  Request $request
     */
    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            echo 'OK';
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}
