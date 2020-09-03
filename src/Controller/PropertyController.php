<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;
use App\Entity\Contact;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Form\ContactType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Notification\ContactNotification;


class PropertyController extends AbstractController
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
     * @Route("/properties", name="property.index")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 12
        );
        return $this->render('property/index.html.twig', [
              'current_menu' => 'properties',
              'properties'   => $properties,
              'form'         => $form->createView()
        ]);
    }

    /**
     * @Route("/properties/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     * @param Request $request
     */
    public function show(Property $property, string $slug, Request $request, ContactNotification $notif): Response
    {
        if($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }

        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notif->notify($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ]);
        }

        return $this->render('property/show.html.twig', [
            'property'     => $property,
            'current_menu' => 'properties',
            'form'         => $form->createView()
        ]);
    }
}
