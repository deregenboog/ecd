<?php

namespace HsBundle\Repository;

use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;

class KlusRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;

    /**
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

    public function getCategory(): string
    {
        return DoelstellingRepositoryInterface::CAT_ACTIVERING;
    }

    public function initDoelstellingcijfers(): void
    {
        $this->addDoelstellingcijfer(
            'Aantal afgerondde klussen',
            '1179',
            'Homeservice',
            function ($doelstelling, $startdatum, $einddatum) {
                $r = $this->getAantalAfgerondeKlussen($doelstelling, $startdatum, $einddatum);

                return $r;
            }
        );
    }

    private function getAantalAfgerondeKlussen($doelstelling, $startdatum, $einddatum)
    {
        return $this->createQueryBuilder('klus')
            ->select('COUNT(klus.id) AS aantal_klussen')
            ->where('klus.status = :status')
            ->andWhere('klus.einddatum BETWEEN :start AND :end')
            ->setParameters([
                'status' => Klus::STATUS_AFGEROND,
                'start' => $startdatum,
                'end' => $einddatum,
            ])
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
