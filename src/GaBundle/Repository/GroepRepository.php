<?php

namespace GaBundle\Repository;

use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Deelname;
use GaBundle\Entity\Klantdossier;
use GaBundle\Entity\Vrijwilligerdossier;

class GroepRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;

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

    public function getCategory(): string
    {
        return DoelstellingRepositoryInterface::CAT_ACTIVERING;
    }

    public function initDoelstellingcijfers(): void
    {
        /**
         * Buurtrestaurants
         *
         */
        $builder = $this->createQueryBuilder('groep')
            ->select('groep.naam')
            ->andWhere("groep.naam LIKE :buurtrestaurantPrefix")
            ->setParameter('buurtrestaurantPrefix', "RBR %")
        ;

        $result = $builder->getQuery()->getResult();
        foreach ($result as $buurtrestaurant) {
            $this->addDoelstellingcijfer(
                "Aantal dagen (avonden) dat er een activiteit is die valt binnen een groep waarvan de naam begint met RBR.",
                "1174",
                $buurtrestaurant['naam']." (avonden open)",
                function($doelstelling, $startdatum, $einddatum) use ($buurtrestaurant) {
                    return $this->getBuurtrestaurantsAvondenOpen($doelstelling,$startdatum,$einddatum, $buurtrestaurant);
                });

            $this->addDoelstellingcijfer(
                "Aantal geregistreerde en anonieme deelnemers aan activiteit die valt binnen eem groep waarvan de naam begint met RBR.",
                "1174",$buurtrestaurant['naam']." (bezoekers)",
                function($doelstelling, $startdatum, $einddatum) use ($buurtrestaurant) {
                    $r =  $this->getBuurtrestaurantsAantalDeelnemers($doelstelling,$startdatum,$einddatum, $buurtrestaurant);
                return $r['aantal_deelnemers'] + $r['aantal_anonieme_deelnemers'];
                });
        }
        /**
         * ErOpUit
         */
        $this->addDoelstellingcijfer(
            "Aantal deelnemers + anonieme deelnemers aan activiteiten die binnen een groep vallen die begint met EOU, plus de groep Losse uitjes.",
            '1173', "ErOpUit",
            function($doelstelling,$startdatum, $einddatum)
            {
                $r =  $this->getAantalErOpUitDeelnemers($doelstelling,$startdatum,$einddatum);
                return $r['aantal_deelnemers'] + $r['aantal_anonieme_deelnemers'];
            }
        );

        /**
         * PsyCafe
         */
        $prefix = "PSY%";
        $this->addDoelstellingcijfer(
            "Aantal deelnemers + anonieme deelnemers aan activiteiten die binnen een groep vallen die begint met PSY.",
            '1176', "PsyCafe",
            function($doelstelling,$startdatum, $einddatum) use ($prefix)
            {
                $r =  $this->getAantalDeelnemersForPrefix($doelstelling,$startdatum,$einddatum,$prefix);
                return $r['aantal_deelnemers'] + $r['aantal_anonieme_deelnemers'];
            }
        );

        /**
         * Sportactiviteiten
         */
        $prefix = "Sport%";
        $this->addDoelstellingcijfer(
            "Aantal deelnemers + anonieme deelnemers aan activiteiten die binnen een groep vallen die begint met Sport.",
            '1736', "Sportactiviteiten",
            function($doelstelling,$startdatum, $einddatum) use ($prefix)
            {
                $r =  $this->getAantalDeelnemersForPrefix($doelstelling,$startdatum,$einddatum,$prefix);
                return $r['aantal_deelnemers'] + $r['aantal_anonieme_deelnemers'];
            }
        );

    }

    private function getBuurtrestaurantsAvondenOpen($doelstelling, \DateTime $startDate, \DateTime $endDate,$buurtrestaurant)
    {
        $builder = $this->createQueryBuilder('groep')
            ->select('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
//            ->addSelect('COUNT(DISTINCT deelname) AS aantal_deelnames')
//            ->addSelect('COUNT(DISTINCT klant) AS aantal_deelnemers')
//            ->addSelect('IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnames')
//            ->leftJoin('groep.werkgebied', 'werkgebied')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
//            ->where('activiteit.datum BETWEEN :start AND :eind')
//            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
//            ->innerJoin(Klantdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
//            ->innerJoin('dossier.klant', 'klant')
            ->andWhere('groep.naam = :groepnaam')
//            ->groupBy('groepnaam')
//            ->orderBy('groep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('groepnaam',$buurtrestaurant['naam'])
//            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        return $builder->getQuery()->getSingleScalarResult();

    }

    private function getBuurtrestaurantsAantalDeelnemers($doelstelling, \DateTime $startDate, \DateTime $endDate,$buurtrestaurant)
    {
        $builder = $this->createQueryBuilder('groep')
//            ->select('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->select('COUNT(DISTINCT deelname) AS aantal_deelnames')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_deelnemers')
            ->addSelect('IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnemers')
//            ->leftJoin('groep.werkgebied', 'werkgebied')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
//            ->where('activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Klantdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.klant', 'klant')
            ->andWhere('groep.naam = :groepnaam')
//            ->groupBy('groepnaam')
//            ->orderBy('groep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('groepnaam',$buurtrestaurant['naam'])
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        return $builder->getQuery()->getSingleResult();

    }

    private function getAantalErOpUitDeelnemers($doelstelling, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->createQueryBuilder('groep')
//            ->select('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->select('COUNT(DISTINCT deelname) AS aantal_deelnames')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_deelnemers')
            ->addSelect('IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnemers')
//            ->leftJoin('groep.werkgebied', 'werkgebied')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
//            ->where('activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Klantdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.klant', 'klant')
            ->andWhere('groep.naam LIKE :EOUprefix')
            ->orWhere('groep.naam = :groepnaam')
//            ->groupBy('groepnaam')
//            ->orderBy('groep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('EOUprefix',"EOU %")
            ->setParameter('groepnaam','Losse uitjes')
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        return $builder->getQuery()->getSingleResult();

    }

    private function getAantalDeelnemersForPrefix($doelstelling, \DateTime $startDate, \DateTime $endDate, $prefix)
    {
        $builder = $this->createQueryBuilder('groep')
//            ->select('COUNT(DISTINCT activiteit) AS aantal_activiteiten')
            ->select('COUNT(DISTINCT deelname) AS aantal_deelnames')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_deelnemers')
            ->addSelect('IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnemers')
//            ->leftJoin('groep.werkgebied', 'werkgebied')
            ->innerJoin('groep.activiteiten', 'activiteit', 'WITH', 'activiteit.datum BETWEEN :start AND :eind')
//            ->where('activiteit.datum BETWEEN :start AND :eind')
            ->innerJoin('activiteit.deelnames', 'deelname', 'WITH', 'deelname.status = :aanwezig')
            ->innerJoin(Klantdossier::class, 'dossier', 'WITH', 'deelname.dossier = dossier')
            ->innerJoin('dossier.klant', 'klant')
            ->andWhere('groep.naam LIKE :prefix')
//            ->groupBy('groepnaam')
//            ->orderBy('groep.naam')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
            ->setParameter('prefix',$prefix)
            ->setParameter('aanwezig', Deelname::STATUS_AANWEZIG)
        ;
        return $builder->getQuery()->getSingleResult();

    }
}
