<?php

namespace GaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Deelname;

class GroepRepository extends EntityRepository
{
    public function countDeelnemers(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->addSelect('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT klantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.klantDeelnames', 'klantDeelname', 'WITH', 'klantDeelname.status = :aanwezig')
            ->innerJoin('klantDeelname.klant', 'klant')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countDeelnemersPerGroep(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select("CONCAT(gaGroep.naam, ' (', werkgebied.naam, ')') AS groep")
            ->addSelect('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT klantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.werkgebied', 'werkgebied')
            ->innerJoin('gaGroep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.klantDeelnames', 'klantDeelname', 'WITH', 'klantDeelname.status = :aanwezig')
            ->innerJoin('klantDeelname.klant', 'klant')
            ->groupBy('groep')
            ->orderBy('gaGroep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countDeelnemersPerStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select('werkgebied.naam AS stadsdeel')
            ->addSelect('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT klantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.klantDeelnames', 'klantDeelname', 'WITH', 'klantDeelname.status = :aanwezig')
            ->innerJoin('klantDeelname.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->groupBy('stadsdeel')
            ->orderBy('werkgebied.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countDeelnemersPerGroepStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select("CONCAT(gaGroep.naam, ' (', werkgebiedGroep.naam, ')') AS groep")
            ->addSelect('werkgebiedKlant.naam AS stadsdeel')
            ->addSelect('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT klantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.klantDeelnames', 'klantDeelname', 'WITH', 'klantDeelname.status = :aanwezig')
            ->innerJoin('klantDeelname.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebiedKlant')
            ->leftJoin('gaGroep.werkgebied', 'werkgebiedGroep')
            ->groupBy('groep, stadsdeel')
            ->orderBy('gaGroep.naam, werkgebiedKlant.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countVrijwilligers(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_vrijwilligers')
            ->innerJoin('gaGroep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.vrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        $data = $builder->getQuery()->getResult();

        $builder = $this->createQueryBuilder('gaGroep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_nieuwe_vrijwilligers')
            ->innerJoin('gaGroep.vrijwilligerlidmaatschappen', 'vrijwilligerLidmaatschap', 'WITH', 'vrijwilligerLidmaatschap.startdatum BETWEEN :start AND :eind')
            ->innerJoin('gaGroep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.vrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        $data[0] = array_merge(current($data), current($builder->getQuery()->getResult()));

        $builder = $this->createQueryBuilder('gaGroep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_gestopte_vrijwilligers')
            ->innerJoin('gaGroep.vrijwilligerlidmaatschappen', 'vrijwilligerLidmaatschap', 'WITH', 'vrijwilligerLidmaatschap.einddatum BETWEEN :start AND :eind')
            ->innerJoin('gaGroep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.vrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        $data[0] = array_merge(current($data), current($builder->getQuery()->getResult()));

        return $data;
    }

    public function countVrijwilligersPerGroep(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select("CONCAT(gaGroep.naam, ' (', werkgebied.naam, ')') AS groep")
            ->addSelect('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT gaVrijwilligerDeelname) AS aantal_vrijwilligers')
            ->addSelect('COUNT(DISTINCT vrijwilliger) AS aantal_unieke_vrijwilligers')
            ->innerJoin('gaGroep.werkgebied', 'werkgebied')
            ->innerJoin('gaGroep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.vrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->groupBy('groep')
            ->orderBy('gaGroep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countVrijwilligersPerGroepStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select("CONCAT(gaGroep.naam, ' (', werkgebiedGroep.naam, ')') AS groep")
            ->addSelect('werkgebiedVrijwilliger.naam AS stadsdeel')
            ->addSelect('COUNT(DISTINCT gaVrijwilligerDeelname) AS aantal_vrijwilligers')
            ->addSelect('COUNT(DISTINCT vrijwilliger) AS aantal_unieke_vrijwilligers')
            ->innerJoin('gaGroep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.vrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebiedVrijwilliger')
            ->leftJoin('gaGroep.werkgebied', 'werkgebiedGroep')
            ->groupBy('groep, stadsdeel')
            ->orderBy('gaGroep.naam, werkgebiedVrijwilliger.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }
}
