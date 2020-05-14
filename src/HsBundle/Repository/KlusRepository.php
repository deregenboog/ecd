<?php

namespace HsBundle\Repository;

use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;

class KlusRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    /**
     * @param AppDateRangeModel $dateRange
     *
     * @return Factuur
     */
    public function findNonLockedByKlantAndDateRange(Klant $klant, AppDateRangeModel $dateRange)
    {
        return $this->createQueryBuilder('factuur')
            ->select('factuur, registratie, declaratie')
            ->leftJoin('factuur.registraties', 'registratie')
            ->leftJoin('factuur.declaraties', 'declaratie')
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

    public static function getCategoryLabel(): string
    {
        // TODO: Implement getCategoryLabel() method.
    }

    public function getVerfijningsas1(): ?array
    {
        // TODO: Implement getVerfijningsas1() method.
    }

    public function getVerfijningsas2(): ?array
    {
        // TODO: Implement getVerfijningsas2() method.
    }
}
