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
            'hulpvraag.startdatum',
            'hulpvraag.geschiktVoorExpat',
            'project.naam',
            'intake.intakeDatum',
            'klant.id',
            'klant.voornaam',
            'klant.achternaam',
            'klant.geboortedatum',
            'klant.laatsteZrm',
            'werkgebied.naam',
            'medewerker.achternaam',
            'stadsdeel.naam',
            'geslacht.volledig',
            'hulpvraagsoort.naam',
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
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->where('hulpvraag.hulpaanbod IS NULL')
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
            ->innerJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort')
            ->innerJoin('izKlant.intake', 'intake')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'stadsdeel')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->andWhere('hulpvraag.einddatum IS NULL') // hulpvraag niet afgesloten
            ->andWhere('hulpvraag.hulpaanbod IS NULL') // hulpvraag niet gekoppeld
            ->andWhere('izKlant.afsluitDatum IS NULL') // klant niet afgesloten
            ->orderBy('hulpvraag.startdatum', 'ASC')
        ;

        // doelgroepen
        if (count($hulpaanbod->getDoelgroepen()) > 0) {
            $builder
                ->leftJoin('hulpvraag.doelgroepen', 'doelgroep')
                ->andWhere('doelgroep.id IS NULL OR doelgroep IN (:doelgroepen)')
                ->setParameter('doelgroepen', $hulpaanbod->getDoelgroepen())
            ;
        }

        // hulpvraagsoorten
        if (count($hulpaanbod->getHulpvraagsoorten()) > 0) {
            $builder
                ->andWhere('hulpvraagsoort IN (:hulpvraagsoorten)')
                ->setParameter('hulpvraagsoorten', $hulpaanbod->getHulpvraagsoorten())
            ;
        }

        // expat
        if ($hulpaanbod->isExpat()) {
            $builder->andWhere('hulpvraag.geschiktVoorExpat = true');
        }

        // geslacht
        if ($hulpaanbod->getVoorkeurGeslacht()) {
            $builder
                ->andWhere('geslacht.id IS NULL OR geslacht.afkorting = :onbekend OR geslacht = :voorkeur_geslacht')
                ->setParameter('onbekend', Geslacht::AFKORTING_ONBEKEND)
                ->setParameter('voorkeur_geslacht', $hulpaanbod->getVoorkeurGeslacht())
            ;
        }

        // dagdeel
        if ($hulpaanbod->getDagdeel()) {
            switch ($hulpaanbod->getDagdeel()) {
                case Koppeling::DAGDEEL_OVERDAG:
                    $dagdelen = [Koppeling::DAGDEEL_OVERDAG];
                    break;
                case Koppeling::DAGDEEL_AVOND:
                    $dagdelen = [
                        Koppeling::DAGDEEL_AVOND,
                        Koppeling::DAGDEEL_AVOND_WEEKEND,
                    ];
                    break;
                case Koppeling::DAGDEEL_WEEKEND:
                    $dagdelen = [
                        Koppeling::DAGDEEL_WEEKEND,
                        Koppeling::DAGDEEL_AVOND_WEEKEND,
                    ];
                    break;
                case Koppeling::DAGDEEL_AVOND_WEEKEND:
                    $dagdelen = [
                        Koppeling::DAGDEEL_AVOND,
                        Koppeling::DAGDEEL_WEEKEND,
                        Koppeling::DAGDEEL_AVOND_WEEKEND,
                    ];
                    break;
                default:
                    $dagdelen = [
                        Koppeling::DAGDEEL_OVERDAG,
                        Koppeling::DAGDEEL_AVOND,
                        Koppeling::DAGDEEL_WEEKEND,
                        Koppeling::DAGDEEL_AVOND_WEEKEND,
                    ];
                    break;
            }
            $builder
                ->andWhere('hulpvraag.dagdeel IS NULL OR hulpvraag.dagdeel IN (:dagdelen)')
                ->setParameter('dagdelen', $dagdelen)
            ;
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }
}
