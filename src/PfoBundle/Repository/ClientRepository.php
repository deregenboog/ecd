<?php

namespace PfoBundle\Repository;

use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ClientRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;

    public function countByGroep(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('groep.naam AS groepnaam')
            ->leftJoin('client.groep', 'groep')
            ->innerJoin('client.verslagen', 'verslag')
            ->groupBy('groepnaam')
        ;

        $this->applyReportFilter($builder, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countByStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('stadsdeel.naam AS stadsdeelnaam')
            ->leftJoin('client.werkgebied', 'stadsdeel')
            ->innerJoin('client.verslagen', 'verslag')
            ->groupBy('stadsdeelnaam')
        ;
        //        dump($builder->getQuery()->getSQL());
        $this->applyReportFilter($builder, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function applyReportFilter(QueryBuilder $builder, \DateTime $startDate, \DateTime $endDate)
    {
        $builder
            ->where('DATE(client.created) BETWEEN :startDate AND :endDate')
            ->orWhere('DATE(verslag.created) BETWEEN :startDate AND :endDate')
            ->setParameters([
                'startDate' => $startDate,
                'endDate' => $endDate,
            ])
        ;
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('client')
            ->select('COUNT(DISTINCT client.id) AS aantal')
        ;
    }

    public function getCategory(): string
    {
        return DoelstellingRepositoryInterface::CAT_HULPVERLENING;
    }

    public function initDoelstellingcijfers(): void
    {
        $this->addDoelstellingcijfer(
            "Aantal trajecten met externe clienten (groep is niet 'Intern') welke nog niet zijn afgesloten, of zijn afgesloten in dit tijdvak.",
            '4130',
            'PFO',
            function ($doelstelling, $startdatum, $einddatum) {
                $r = $this->getAantalClienten($doelstelling, $startdatum, $einddatum);

                return $r;
            }
        );
    }

    private function getAantalClienten($doelstelling, $startdatum, $einddatum)
    {
        $builder = $this->getCountBuilder();
        $builder
            ->innerJoin('client.groep', 'groep')
            ->innerJoin('client.verslagen', 'verslag')
            ->where('DATE(client.afsluitdatum) BETWEEN :startDate AND :endDate')
            ->orWhere('client.afsluitdatum IS NULL')
            ->setParameter('startDate', $startdatum)
            ->setParameter('endDate', $einddatum)
        ;

        return $builder->getQuery()->getSingleScalarResult();
    }
}
