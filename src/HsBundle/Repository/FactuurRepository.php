<?php

namespace HsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Form\Model\AppDateRangeModel;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;

class FactuurRepository extends EntityRepository
{
    /**
     * @param AppDateRangeModel $dateRange
     *
     * @return Factuur
     */
    public function findNonLockedByKlantAndDateRange(Klant $klant, AppDateRangeModel $dateRange)
    {
        return $this->createQueryBuilder('factuur')
            ->where('factuur.locked = false')
            ->andWhere('factuur.klant = :klant')
            ->andWhere('factuur.datum BETWEEN :start AND :end')
            ->setParameters([
                'klant' => $klant,
                'start' => $dateRange->getStart(),
                'end' => $dateRange->getEnd(),
            ])
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByKlantAndDateRange(Klant $klant, AppDateRangeModel $dateRange)
    {
        return $this->createQueryBuilder('factuur')
            ->where('factuur.klant = :klant')
            ->andWhere('factuur.datum BETWEEN :start AND :end')
            ->setParameters([
                'klant' => $klant,
                'start' => $dateRange->getStart(),
                'end' => $dateRange->getEnd(),
            ])
            ->getQuery()
            ->getResult()
        ;
    }
}
