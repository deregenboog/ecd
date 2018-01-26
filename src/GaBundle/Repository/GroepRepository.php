<?php

namespace GaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Deelname;
use GaBundle\Entity\Klantdossier;
use GaBundle\Entity\Vrijwilligerdossier;

class GroepRepository extends EntityRepository
{
    public function countDeelnemers(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('groep')
            ->select('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT deelname) AS aantal_deelnames')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_deelnemers')
            ->addSelect('IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnames')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Klantdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.klant', 'klant')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getSingleResult();
    }

    public function countDeelnemersPerGroep(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('groep')
            ->select("CONCAT(groep.naam, ' (', werkgebied.naam, ')') AS groepnaam")
            ->addSelect('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT deelname) AS aantal_deelnames')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_deelnemers')
            ->addSelect('IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnames')
            ->leftJoin('groep.werkgebied', 'werkgebied')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Klantdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.klant', 'klant')
            ->groupBy('groepnaam')
            ->orderBy('groep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countDeelnemersPerStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('groep')
            ->select('werkgebied.naam AS stadsdeel')
            ->addSelect('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT deelname) AS aantal_deelnames')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_deelnemers')
            ->addSelect('IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnames')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Klantdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.klant', 'klant')
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
        $builder = $this->createQueryBuilder('groep')
            ->select("CONCAT(groep.naam, ' (', werkgebiedGroep.naam, ')') AS groepnaam")
            ->addSelect('werkgebiedKlant.naam AS stadsdeel')
            ->addSelect('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT deelname) AS aantal_deelnames')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_deelnemers')
            ->addSelect('IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnames')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Klantdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebiedKlant')
            ->leftJoin('groep.werkgebied', 'werkgebiedGroep')
            ->groupBy('groepnaam, stadsdeel')
            ->orderBy('groep.naam, werkgebiedKlant.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countVrijwilligers(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('groep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_vrijwilligersdeelnames')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Vrijwilligerdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.vrijwilliger', 'vrijwilliger')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        $data = $builder->getQuery()->getSingleResult();

        $builder = $this->createQueryBuilder('groep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_nieuwe_vrijwilligers')
            ->innerJoin('groep.lidmaatschappen', 'lidmaatschap', 'WITH', 'lidmaatschap.startdatum BETWEEN :start AND :eind')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Vrijwilligerdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier AND lidmaatschap.dossier = dossier')
            ->innerJoin('dossier.vrijwilliger', 'vrijwilliger')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        $data = array_merge($data, $builder->getQuery()->getSingleResult());

        $builder = $this->createQueryBuilder('groep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_gestopte_vrijwilligers')
            ->innerJoin('groep.lidmaatschappen', 'lidmaatschap', 'WITH', 'lidmaatschap.einddatum BETWEEN :start AND :eind')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Vrijwilligerdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier AND lidmaatschap.dossier = dossier')
            ->innerJoin('dossier.vrijwilliger', 'vrijwilliger')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        $data = array_merge($data, $builder->getQuery()->getSingleResult());

        return $data;
    }

    public function countVrijwilligersPerGroep(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('groep')
            ->select("CONCAT(groep.naam, ' (', werkgebied.naam, ')') AS groepnaam")
            ->addSelect('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT deelname) AS aantal_vrijwilligersdeelnames')
            ->addSelect('COUNT(DISTINCT vrijwilliger) AS aantal_vrijwilligers')
            ->leftJoin('groep.werkgebied', 'werkgebied')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Vrijwilligerdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.vrijwilliger', 'vrijwilliger')
            ->groupBy('groepnaam')
            ->orderBy('groep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countVrijwilligersPerGroepStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('groep')
            ->select("CONCAT(groep.naam, ' (', werkgebiedGroep.naam, ')') AS groepnaam")
            ->addSelect('werkgebiedVrijwilliger.naam AS stadsdeel')
            ->addSelect('COUNT(DISTINCT deelname) AS aantal_vrijwilligersdeelnames')
            ->addSelect('COUNT(DISTINCT vrijwilliger) AS aantal_vrijwilligers')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Vrijwilligerdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebiedVrijwilliger')
            ->leftJoin('groep.werkgebied', 'werkgebiedGroep')
            ->groupBy('groepnaam, stadsdeel')
            ->orderBy('groep.naam, werkgebiedVrijwilliger.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }
}
