<?php

namespace IzBundle\Service;

use AppBundle\Entity\Geslacht;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Koppeling;

class HulpvraagDao extends AbstractDao implements HulpvraagDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'hulpvraag.startdatum',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'geslacht.volledig',
            'hulpvraag.startdatum',
            'intake.intakeDatum',
            'izKlant.dierenbezitter',
            'klant.id',
            'klant.voornaam',
            'klant.achternaam',
            'klant.geboortedatum',
            'klant.laatsteZrm',
            'klant.roker',
            'medewerker.voornaam',
            'project.naam',
            'stadsdeel.naam',
        ],
    ];

    protected $class = Hulpvraag::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpvraag')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->leftJoin('izKlant.intake', 'intake')
            ->innerJoin('hulpvraag.project', 'project')
            ->innerJoin('hulpvraag.medewerker', 'medewerker')
            ->leftJoin('hulpvraag.koppeling', 'koppeling')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'stadsdeel')
            ->where('koppeling.id IS NULL')
            ->andWhere('hulpvraag.einddatum IS NULL')
            ->andWhere('izKlant.afsluiting IS NULL')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Hulpvraag $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Hulpvraag $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Hulpvraag $entity)
    {
        $this->doDelete($entity);
    }

    public function findMatching(Hulpaanbod $hulpaanbod, $page = 1)
    {
        $builder = $this->repository->createQueryBuilder('hulpvraag')
            ->select('hulpvraag, izKlant, klant')
            ->innerJoin('hulpvraag.project', 'project', 'WITH', 'project.heeftKoppelingen = true')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->leftJoin('hulpvraag.reserveringen', 'reservering')
            ->leftJoin('hulpvraag.koppeling', 'koppeling')
            ->leftJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort')
            ->innerJoin('izKlant.intake', 'intake')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'stadsdeel')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->andWhere('hulpvraag.startdatum <= :today') // hulpvraag gestart
            ->andWhere('hulpvraag.einddatum IS NULL OR hulpvraag.einddatum >= :today') // hulpvraag niet afgesloten
            ->andWhere('reservering.id IS NULL OR :today NOT BETWEEN reservering.startdatum AND reservering.einddatum') // hulpvraag niet gereserveerd
            ->andWhere('koppeling.id IS NULL') // hulpvraag niet gekoppeld
            ->andWhere('izKlant.afsluitDatum IS NULL') // klant niet afgesloten
            ->setParameters([
                'today' => new \DateTime('today'),
            ])
        ;

        // hulpvraagsoorten
        if (count($hulpaanbod->getHulpvraagsoorten()) > 0) {
            $builder
                ->andWhere('hulpvraagsoort IN (:hulpvraagsoorten)')
                ->setParameter('hulpvraagsoorten', $hulpaanbod->getHulpvraagsoorten())
            ;
        }

        // stadsdelen
        if (count($hulpaanbod->getVoorkeurStadsdelen()) > 0) {
            $builder
                ->andWhere('stadsdeel IN (:stadsdelen)')
                ->setParameter('stadsdelen', $hulpaanbod->getVoorkeurStadsdelen())
            ;
        }
        if ($hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->getWerkgebied()) {
            $builder
                ->leftJoin('hulpvraag.voorkeurStadsdelen', 'voorkeurStadsdeel')
                ->andWhere('voorkeurStadsdeel.naam IS NULL OR voorkeurStadsdeel = :stadsdeel')
                ->setParameter('stadsdeel', $hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->getWerkgebied())
            ;
        }

        // roken
        if ($hulpaanbod->isVoorkeurNietRoker()) {
            $builder->andWhere('klant.roker = false');
        }
        if ($hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->isRoker()) {
            $builder->andWhere('hulpvraag.voorkeurNietRoker = false');
        }

        // huisdieren
        if ($hulpaanbod->isVoorkeurGeenDieren()) {
            $builder->andWhere('klant.dierenbezitter = false');
        }
        if ($hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->isDierenbezitter()) {
            $builder->andWhere('hulpvraag.voorkeurGeenDieren = false');
        }

        // geslacht
        if ($hulpaanbod->getVoorkeurGeslacht()) {
            $builder
                ->andWhere('geslacht.id IS NULL OR geslacht = :voorkeur_geslacht')
                ->setParameter('voorkeur_geslacht', $hulpaanbod->getVoorkeurGeslacht())
            ;
        }
        if ($hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->getGeslacht()) {
            $builder
                ->andWhere('hulpvraag.voorkeurGeslacht IS NULL OR hulpvraag.voorkeurGeslacht = :geslacht')
                ->setParameter('geslacht', $hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->getGeslacht())
            ;
        }

        // dagen
        if ($hulpaanbod->getBeschikbareDagen(true) > 0) {
            $builder
                ->andWhere('hulpvraag.beschikbareDagen = 0 OR BIT_AND(hulpvraag.beschikbareDagen, :beschikbare_dagen) > 0')
                ->setParameter('beschikbare_dagen', $hulpaanbod->getBeschikbareDagen(true))
            ;
        }

        // minimale leeftijd
        if ($hulpaanbod->getVoorkeurMinLeeftijd()) {
            $builder
                ->andWhere('klant.geboortedatum IS NULL OR YEAR(klant.geboortedatum) <= :geboortedatum_min')
                ->setParameter('geboortedatum_min', date('Y') - $hulpaanbod->getVoorkeurMinLeeftijd())
            ;
        }
        if ($hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->getGeboortedatum()) {
            $builder
                ->andWhere('hulpvraag.voorkeurMinLeeftijd IS NULL OR hulpvraag.voorkeurMinLeeftijd <= :leeftijd_min')
                ->setParameter('leeftijd_min', (new \DateTime())->diff($hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->getGeboortedatum())->y)
            ;
        }

        // maximale leeftijd
        if ($hulpaanbod->getVoorkeurMaxLeeftijd()) {
            $builder
                ->andWhere('klant.geboortedatum IS NULL OR YEAR(klant.geboortedatum) >= :geboortedatum_max')
                ->setParameter('geboortedatum_max', date('Y') - $hulpaanbod->getVoorkeurMaxLeeftijd())
            ;
        }
        if ($hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->getGeboortedatum()) {
            $builder
                ->andWhere('hulpvraag.voorkeurMaxLeeftijd IS NULL OR hulpvraag.voorkeurMaxLeeftijd >= :leeftijd_max')
                ->setParameter('leeftijd_max', (new \DateTime())->diff($hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->getGeboortedatum())->y)
            ;
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }
}
