<?php

namespace InloopBundle\Repository;

use AppBundle\Entity\Geslacht;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class SchorsingRepository extends EntityRepository
{
    public function filterByLocatie(QueryBuilder $builder, Locatie $locatie)
    {
        $builder
            ->innerJoin('schorsing.locaties', 'locatie', 'WITH', 'locatie = :locatie')
            ->setParameter('locatie', $locatie)
        ;
    }

    public function filterByGeslacht(QueryBuilder $builder, Geslacht $geslacht)
    {
        $builder
            ->innerJoin('schorsing.klant', 'klant', 'WITH', 'klant.geslacht = :geslacht')
            ->setParameter('geslacht', $geslacht)
        ;
    }

    public function filterByDateRange(QueryBuilder $builder, AppDateRangeModel $dateRange)
    {
        if ($dateRange->getStart() instanceof \DateTime) {
            $builder
                ->andWhere('schorsing.datumVan >= :start_date')
                ->setParameter('start_date', $dateRange->getStart())
            ;
        }

        if ($dateRange->getEnd() instanceof \DateTime) {
            $builder
                ->andWhere('schorsing.datumVan <= :end_date')
                ->setParameter('end_date', $dateRange->getEnd())
            ;
        }
    }
}
