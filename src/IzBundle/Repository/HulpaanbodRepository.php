<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use Doctrine\Common\Collections\ArrayCollection;

class HulpaanbodRepository extends EntityRepository
{
    public function findMatching(Hulpvraag $hulpvraag)
    {
        $builder = $this->createQueryBuilder('hulpaanbod')
            ->select('hulpaanbod, izVrijwilliger, vrijwilliger')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izVrijwilliger.intake', 'intake')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->andWhere('hulpaanbod.einddatum IS NULL') // hulpaanbod niet afgesloten
            ->andWhere('hulpaanbod.hulpvraag IS NULL') // hulpaanbod niet gekoppeld
            ->andWhere('izVrijwilliger.afsluitDatum IS NULL') // vrijwilliger niet afgesloten
            ->orderBy('hulpaanbod.startdatum', 'ASC')
        ;

        // doelgroepen
        if (count($hulpvraag->getDoelgroepen()) > 0) {
            $builder
                ->leftJoin('hulpaanbod.doelgroepen', 'doelgroep')
                ->andWhere('doelgroep.id IS NULL OR doelgroep IN (:doelgroepen)')
                ->setParameter('doelgroepen', $hulpvraag->getDoelgroepen())
            ;
        }

        // hulpvraagsoorten
        $hulpvraagsoorten = array_filter(array_merge(
            [$hulpvraag->getPrimaireHulpvraagsoort()],
            $hulpvraag->getSecundaireHulpvraagsoorten()->toArray()
        ));
        if (count($hulpvraagsoorten) > 0) {
            $builder
                ->leftJoin('hulpaanbod.hulpvraagsoorten', 'hulpvraagsoort')
                ->andWhere('hulpvraagsoort.id IS NULL OR hulpvraagsoort IN (:hulpvraagsoorten)')
                ->setParameter('hulpvraagsoorten', $hulpvraagsoorten)
            ;
        }

        // taal
        if (!$hulpvraag->isSpreektNederlands()) {
            $builder->andWhere('hulpaanbod.voorkeurVoorNederlands = false');
        }

        // dagdeel
        if ($hulpvraag->getDagdeel()) {
            $builder
                ->andWhere('hulpaanbod.dagdeel = :dagdeel')
                ->setParameter('dagdeel', $hulpvraag->getDagdeel())
            ;
        }

        // stadsdeel
        if ($hulpvraag->getIzKlant()->getKlant()->getWerkgebied()) {
            $builder
                ->andWhere('vrijwilliger.werkgebied = :stadsdeel')
                ->setParameter('stadsdeel', $hulpvraag->getIzKlant()->getKlant()->getWerkgebied())
            ;
        }

        return $builder->getQuery()->getResult();
    }
}
