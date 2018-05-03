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
            'hulpaanbod.startdatum',
            'hulpaanbod.expat',
            'hulpaanbod.coachend',
            'project.naam',
            'intake.intakeDatum',
            'vrijwilliger.id',
            'vrijwilliger.voornaam',
            'vrijwilliger.achternaam',
            'vrijwilliger.geboortedatum',
            'vrijwilliger.werkgebied',
            'vrijwilliger.vogAangevraagd',
            'vrijwilliger.vogAanwezig',
            'vrijwilliger.overeenkomstAanwezig',
            'werkgebied.naam',
            'medewerker.achternaam',
            'stadsdeel.naam',
            'geslacht.volledig',
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
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
            ->where('hulpaanbod.hulpvraag IS NULL')
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
            ->innerJoin('izVrijwilliger.intake', 'intake')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'stadsdeel')
            ->leftJoin('vrijwilliger.geslacht', 'geslacht')
            ->andWhere('hulpaanbod.einddatum IS NULL') // hulpaanbod niet afgesloten
            ->andWhere('hulpaanbod.hulpvraag IS NULL') // hulpaanbod niet gekoppeld
            ->andWhere('izVrijwilliger.afsluitDatum IS NULL') // vrijwilliger niet afgesloten
            ->orderBy('hulpaanbod.startdatum', 'ASC')
        ;

        // doelgroepen
        if ($hulpvraag->getDoelgroep()) {
            $builder
                ->leftJoin('hulpaanbod.doelgroepen', 'doelgroep')
                ->andWhere('doelgroep.id IS NULL OR doelgroep = (:doelgroep)')
                ->setParameter('doelgroep', $hulpvraag->getDoelgroep())
            ;
        }

        // hulpvraagsoorten
        if (count($hulpvraagsoorten) > 0) {
            $builder
                ->leftJoin('hulpaanbod.hulpvraagsoorten', 'hulpvraagsoort')
                ->andWhere('hulpvraagsoort.id IS NULL OR hulpvraagsoort = :hulpvraagsoort')
                ->setParameter('hulpvraagsoort', $hulpvraag->getHulpvraagsoort())
            ;
        }

        // expat
        if (!$hulpvraag->isGeschiktVoorExpat()) {
            $builder->andWhere('hulpaanbod.expat IS NULL OR hulpaanbod.expat = false');
        }

        // geslacht
        if ($hulpvraag->getVoorkeurGeslacht()) {
            $builder
                ->andWhere('geslacht.id IS NULL OR geslacht.afkorting = :onbekend OR geslacht = :voorkeur_geslacht')
                ->setParameter('onbekend', Geslacht::AFKORTING_ONBEKEND)
                ->setParameter('voorkeur_geslacht', $hulpvraag->getVoorkeurGeslacht())
            ;
        }

        // dagdeel
        if ($hulpvraag->getDagdeel()) {
            switch ($hulpvraag->getDagdeel()) {
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
                ->andWhere('hulpaanbod.dagdeel IS NULL OR hulpaanbod.dagdeel IN (:dagdelen)')
                ->setParameter('dagdelen', $dagdelen)
            ;
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }
}
