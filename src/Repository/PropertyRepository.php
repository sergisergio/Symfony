<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use App\Entity\PropertySearch;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }



    /**
     * @return Query
     */
    public function findAllVisibleQuery(PropertySearch $search): Query
    {
        $query = $this->findVisibleQuery();
        if ($search->getMaxPrice()) {
            $query = $query->andWhere('p.price <= :maxprice');
            $query->setParameter('maxprice', $search->getMaxprice());
        }
        if ($search->getMinSurface()) {
            $query = $query->andWhere('p.surface >= :minsurface');
            $query->setParameter('minsurface', $search->getMinSurface());
        }
        if ($search->getOptions()->count() > 0) {
            $k = 0;
            foreach($search->getOptions() as $option) {
                $k++;
                $query = $query
                    ->andWhere(":option$k MEMBER OF p.options")
                    ->setParameter("option$k", $option);
            }
        }
        return $query->getQuery();
    }

    /**
     * @return Property[]
     */
    public function findLatest(): array
    {
        return $this->findVisibleQuery()
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sold = false');
    }
}
