<?php

namespace GaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\GaActiviteit;

class GaGroepRepository extends EntityRepository
{
    public function countDeelnemers(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select('klant.werkgebied AS stadsdeel')
            ->addSelect('COUNT(DISTINCT gaActiviteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT gaKlantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit', 'WITH', 'gaActiviteit.datum BETWEEN :start AND :eind')
            ->innerJoin('gaActiviteit.gaKlantDeelnames', 'gaKlantDeelname', 'WITH', 'gaKlantDeelname.status = :aanwezig')
            ->innerJoin('gaKlantDeelname.klant', 'klant')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', GaActiviteit::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countDeelnemersPerGroep(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select("CONCAT(gaGroep.naam, ' (', gaGroep.werkgebied, ')') AS groep")
            ->addSelect('COUNT(DISTINCT gaActiviteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT gaKlantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit', 'WITH', 'gaActiviteit.datum BETWEEN :start AND :eind')
            ->innerJoin('gaActiviteit.gaKlantDeelnames', 'gaKlantDeelname', 'WITH', 'gaKlantDeelname.status = :aanwezig')
            ->innerJoin('gaKlantDeelname.klant', 'klant')
            ->groupBy('groep')
            ->orderBy('gaGroep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', GaActiviteit::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countDeelnemersPerStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select('klant.werkgebied AS stadsdeel')
            ->addSelect('COUNT(DISTINCT gaActiviteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT gaKlantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit', 'WITH', 'gaActiviteit.datum BETWEEN :start AND :eind')
            ->innerJoin('gaActiviteit.gaKlantDeelnames', 'gaKlantDeelname', 'WITH', 'gaKlantDeelname.status = :aanwezig')
            ->innerJoin('gaKlantDeelname.klant', 'klant')
            ->groupBy('klant.werkgebied')
            ->orderBy('klant.werkgebied')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', GaActiviteit::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countDeelnemersPerGroepStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select("CONCAT(gaGroep.naam, ' (', gaGroep.werkgebied, ')') AS groep")
            ->addSelect('klant.werkgebied AS stadsdeel')
            ->addSelect('COUNT(DISTINCT gaActiviteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT gaKlantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit', 'WITH', 'gaActiviteit.datum BETWEEN :start AND :eind')
            ->innerJoin('gaActiviteit.gaKlantDeelnames', 'gaKlantDeelname', 'WITH', 'gaKlantDeelname.status = :aanwezig')
            ->innerJoin('gaKlantDeelname.klant', 'klant')
            ->groupBy('groep, klant.werkgebied')
            ->orderBy('gaGroep.naam, klant.werkgebied')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', GaActiviteit::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countVrijwilligers(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_vrijwilligers')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit', 'WITH', 'gaActiviteit.datum BETWEEN :start AND :eind')
            ->innerJoin('gaActiviteit.gaVrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', GaActiviteit::STATUS_AANWEZIG)
        ;
        $data = $builder->getQuery()->getResult();

        $builder = $this->createQueryBuilder('gaGroep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_nieuwe_vrijwilligers')
            ->innerJoin('gaGroep.gaVrijwilligerLeden', 'gaVrijwilligerLidmaatschap', 'WITH', 'gaVrijwilligerLidmaatschap.startdatum BETWEEN :start AND :eind')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit', 'WITH', 'gaActiviteit.datum BETWEEN :start AND :eind')
            ->innerJoin('gaActiviteit.gaVrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', GaActiviteit::STATUS_AANWEZIG)
        ;
        $data[0] = array_merge(current($data), current($builder->getQuery()->getResult()));

        $builder = $this->createQueryBuilder('gaGroep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_gestopte_vrijwilligers')
            ->innerJoin('gaGroep.gaVrijwilligerLeden', 'gaVrijwilligerLidmaatschap', 'WITH', 'gaVrijwilligerLidmaatschap.einddatum BETWEEN :start AND :eind')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit', 'WITH', 'gaActiviteit.datum BETWEEN :start AND :eind')
            ->innerJoin('gaActiviteit.gaVrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', GaActiviteit::STATUS_AANWEZIG)
        ;
        $data[0] = array_merge(current($data), current($builder->getQuery()->getResult()));

        return $data;
    }

    public function countVrijwilligersPerGroep(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select("CONCAT(gaGroep.naam, ' (', gaGroep.werkgebied, ')') AS groep")
            ->addSelect('COUNT(DISTINCT gaActiviteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT gaVrijwilligerDeelname) AS aantal_vrijwilligers')
            ->addSelect('COUNT(DISTINCT vrijwilliger) AS aantal_unieke_vrijwilligers')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit', 'WITH', 'gaActiviteit.datum BETWEEN :start AND :eind')
            ->innerJoin('gaActiviteit.gaVrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->groupBy('groep')
            ->orderBy('gaGroep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', GaActiviteit::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }

    public function countVrijwilligersPerGroepStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('gaGroep')
            ->select("CONCAT(gaGroep.naam, ' (', gaGroep.werkgebied, ')') AS groep")
            ->addSelect('vrijwilliger.werkgebied AS stadsdeel')
            ->addSelect('COUNT(DISTINCT gaVrijwilligerDeelname) AS aantal_vrijwilligers')
            ->addSelect('COUNT(DISTINCT vrijwilliger) AS aantal_unieke_vrijwilligers')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit', 'WITH', 'gaActiviteit.datum BETWEEN :start AND :eind')
            ->innerJoin('gaActiviteit.gaVrijwilligerDeelnames', 'gaVrijwilligerDeelname', 'WITH', 'gaVrijwilligerDeelname.status = :aanwezig')
            ->innerJoin('gaVrijwilligerDeelname.vrijwilliger', 'vrijwilliger')
            ->groupBy('groep, vrijwilliger.werkgebied')
            ->orderBy('gaGroep.naam, vrijwilliger.werkgebied')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('aanwezig', GaActiviteit::STATUS_AANWEZIG)
        ;

        return $builder->getQuery()->getResult();
    }
}
