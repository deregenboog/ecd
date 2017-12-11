<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Postcodegebied;

class IzProjectRepository extends EntityRepository
{
    /**
     * Finds all projects that are active on the given date.
     *
     * @param \DateTime $date
     * @return array
     */
    public function findActive(\DateTime $date = null)
    {
        $builder = $this->createQueryBuilder('p')
            ->where('p.startdatum <= :date')
            ->andWhere('p.einddatum IS NULL OR p.einddatum > :date')
            ->orderBy('p.naam', 'ASC')
            ->setParameter('date', $date ?: new \DateTime())
        ;

        return $builder->getQuery()->getResult();
    }
}
