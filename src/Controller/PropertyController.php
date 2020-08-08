<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/biens", name="property.index")
     */
    public function index()
    {
        // $property = new Property();
        //     $property->setTitle('Mon premier bien')
        //         ->setPrice(200000)
        //         ->setRooms(4)
        //         ->setBedrooms(3)
        //         ->setDescription('Une petite description')
        //         ->setSurface(60)
        //         ->setFloor(1)
        //         ->setHeat(1)
        //         ->setCity('Paris')
        //         ->setAddress('17 Place Saint-Pierre')
        //         ->setPostalCode('75018');
        // $em = $this->getDoctrine()->getManager();
        // $em->persist($property);
        // $em->flush();

        //$repo = $this->getDoctrine()->getRepository(Property::class);
        //dump($repo);

        $property = $this->repository->findAllVisible();
        dump($property);
        $this->em->flush();
        return $this->render('property/index.html.twig', [
              'current_menu' => 'properties'
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Property $property, string $slug)
    {
        if($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }

        return $this->render('property/show.html.twig', [
            'property'     => $property,
            'current_menu' => 'properties'
        ]);
    }
}
