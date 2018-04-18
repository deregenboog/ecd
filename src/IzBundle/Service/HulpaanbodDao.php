<?php

namespace IzBundle\Service;

use AppBundle\Entity\Geslacht;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Koppeling;

class HulpaanbodDao extends AbstractDao implements HulpaanbodDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'hulpaanbod.startdatum',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'geslacht.volledig',
            'hulpaanbod.startdatum',
            'intake.intakeDatum',
            'medewerker.voornaam',
            'project.naam',
            'stadsdeel.naam',
            'vrijwilliger.achternaam',
            'vrijwilliger.dierenbezitter',
            'vrijwilliger.id',
            'vrijwilliger.geboortedatum',
            'vrijwilliger.overeenkomstAanwezig',
            'vrijwilliger.roker',
            'vrijwilliger.vogAangevraagd',
            'vrijwilliger.vogAanwezig',
            'vrijwilliger.voornaam',
        ],
    ];

    protected $class = Hulpaanbod::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpaanbod')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->leftJoin('izVrijwilliger.intake', 'intake')
            ->innerJoin('hulpaanbod.project', 'project')
            ->innerJoin('hulpaanbod.medewerker', 'medewerker')
            ->leftJoin('hulpaanbod.koppeling', 'koppeling')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'stadsdeel')
            ->where('koppeling.id IS NULL')
            ->andWhere('hulpaanbod.einddatum IS NULL')
            ->andWhere('izVrijwilliger.afsluiting IS NULL')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Hulpaanbod $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Hulpaanbod $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Hulpaanbod $entity)
    {
        $this->doDelete($entity);
    }

    public function findMatching(Hulpvraag $hulpvraag, $page = 1)
    {
        $builder = $this->repository->createQueryBuilder('hulpaanbod')
            ->select('hulpaanbod, izVrijwilliger, vrijwilliger')
            ->innerJoin('hulpaanbod.project', 'project', 'WITH', 'project.heeftKoppelingen = true')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->leftJoin('hulpaanbod.reserveringen', 'reservering')
            ->leftJoin('hulpaanbod.hulpvraagsoorten', 'hulpvraagsoort')
            ->leftJoin('hulpaanbod.koppeling', 'koppeling')
            ->innerJoin('izVrijwilliger.intake', 'intake')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'stadsdeel')
            ->leftJoin('vrijwilliger.geslacht', 'geslacht')
            ->andWhere('hulpaanbod.startdatum <= :today') // hulpaanbod gestart
            ->andWhere('hulpaanbod.einddatum IS NULL OR hulpaanbod.einddatum >= :today') // hulpaanbod niet afgesloten
            ->andWhere('reservering.id IS NULL OR :today NOT BETWEEN reservering.startdatum AND reservering.einddatum') // hulpaanbod niet gereserveerd
            ->andWhere('koppeling.id IS NULL') // hulpaanbod niet gekoppeld
            ->andWhere('izVrijwilliger.afsluitDatum IS NULL') // vrijwilliger niet afgesloten
            ->setParameters([
                'today' => new \DateTime('today'),
            ])
        ;

        // hulpvraagsoorten
        if ($hulpvraag->getHulpvraagsoort()) {
            $builder
                ->andWhere('hulpvraagsoort.id IS NULL OR hulpvraagsoort = :hulpvraagsoort')
                ->setParameter('hulpvraagsoort', $hulpvraag->getHulpvraagsoort())
            ;
        }

        // stadsdelen
        if (count($hulpvraag->getVoorkeurStadsdelen()) > 0) {
            $builder
                ->andWhere('stadsdeel IN (:stadsdelen)')
                ->setParameter('stadsdelen', $hulpvraag->getVoorkeurStadsdelen())
            ;
        }
        if ($hulpvraag->getIzKlant()->getKlant()->getWerkgebied()) {
            $builder
                ->leftJoin('hulpaanbod.voorkeurStadsdelen', 'voorkeurStadsdeel')
                ->andWhere('voorkeurStadsdeel.naam IS NULL OR voorkeurStadsdeel = :stadsdeel')
                ->setParameter('stadsdeel', $hulpvraag->getIzKlant()->getKlant()->getWerkgebied())
            ;
        }

        // roken
        if ($hulpvraag->isVoorkeurNietRoker()) {
            $builder->andWhere('vrijwilliger.roker = false');
        }
        if ($hulpvraag->getIzKlant()->getKlant()->isRoker()) {
            $builder->andWhere('hulpaanbod.voorkeurNietRoker = false');
        }

        // huisdieren
        if ($hulpvraag->isVoorkeurGeenDieren()) {
            $builder->andWhere('vrijwilliger.dierenbezitter = false');
        }
        if ($hulpvraag->getIzKlant()->getKlant()->isDierenbezitter()) {
            $builder->andWhere('hulpaanbod.voorkeurGeenDieren = false');
        }

        // geslacht
        if ($hulpvraag->getVoorkeurGeslacht()) {
            $builder
                ->andWhere('geslacht.id IS NULL OR geslacht = :voorkeur_geslacht')
                ->setParameter('voorkeur_geslacht', $hulpvraag->getVoorkeurGeslacht())
            ;
        }
        if ($hulpvraag->getIzKlant()->getKlant()->getGeslacht()) {
            $builder
                ->andWhere('hulpaanbod.voorkeurGeslacht IS NULL OR hulpaanbod.voorkeurGeslacht = :geslacht')
                ->setParameter('geslacht', $hulpvraag->getIzKlant()->getKlant()->getGeslacht())
            ;
        }

        // dagen
        if ($hulpvraag->getBeschikbareDagen(true) > 0) {
            $builder
                ->andWhere('hulpaanbod.beschikbareDagen = 0 OR BIT_AND(hulpaanbod.beschikbareDagen, :beschikbare_dagen) > 0')
                ->setParameter('beschikbare_dagen', $hulpvraag->getBeschikbareDagen(true))
            ;
        }

        // minimale leeftijd
        if ($hulpvraag->getVoorkeurMinLeeftijd()) {
            $builder
                ->andWhere('vrijwilliger.geboortedatum IS NULL OR YEAR(vrijwilliger.geboortedatum) <= :geboortedatum_min')
                ->setParameter('geboortedatum_min', date('Y') - $hulpvraag->getVoorkeurMinLeeftijd())
            ;
        }
        if ($hulpvraag->getIzKlant()->getKlant()->getGeboortedatum()) {
            $builder
                ->andWhere('hulpaanbod.voorkeurMinLeeftijd IS NULL OR hulpaanbod.voorkeurMinLeeftijd <= :leeftijd_min')
                ->setParameter('leeftijd_min', (new \DateTime())->diff($hulpvraag->getIzKlant()->getKlant()->getGeboortedatum())->y)
            ;
        }

        // maximale leeftijd
        if ($hulpvraag->getVoorkeurMaxLeeftijd()) {
            $builder
                ->andWhere('vrijwilliger.geboortedatum IS NULL OR YEAR(vrijwilliger.geboortedatum) >= :geboortedatum_max')
                ->setParameter('geboortedatum_max', date('Y') - $hulpvraag->getVoorkeurMaxLeeftijd())
            ;
        }
        if ($hulpvraag->getIzKlant()->getKlant()->getGeboortedatum()) {
            $builder
                ->andWhere('hulpaanbod.voorkeurMaxLeeftijd IS NULL OR hulpaanbod.voorkeurMaxLeeftijd >= :leeftijd_max')
                ->setParameter('leeftijd_max', (new \DateTime())->diff($hulpvraag->getIzKlant()->getKlant()->getGeboortedatum())->y)
            ;
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }
}
