<?php

namespace IzBundle\Service;

use AppBundle\Entity\Geslacht;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Hulp;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Filter\HulpaanbodFilter;

class HulpaanbodDao extends AbstractDao implements HulpaanbodDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'hulpaanbod.startdatum',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'hulpaanbod.startdatum',
            'hulpaanbod.expat',
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
            'medewerker.voornaam',
            'stadsdeel.naam',
            'geslacht.volledig',
            'hulpvraagsoort.naam',
            'doelgroep.naam',
        ],
        'wrap-queries' => true, // because of ordering by to-many relation
    ];

    protected $class = Hulpaanbod::class;

    protected $hulpsoortenZonderKoppelingen = [];

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpaanbod')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->leftJoin('izVrijwilliger.intake', 'intake')
            ->innerJoin('hulpaanbod.project', 'project')
            ->innerJoin('hulpaanbod.medewerker', 'medewerker')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
            ->innerJoin('hulpaanbod.hulpvraagsoorten','hulpvraagsoort')
            ->innerJoin('hulpaanbod.doelgroepen','doelgroep')
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

    public function findMatching(Hulpvraag $hulpvraag, $page = null, HulpaanbodFilter $filter = null)
    {

        $builder = $this->repository->createQueryBuilder('hulpaanbod')
            ->select('hulpaanbod, izVrijwilliger, vrijwilliger')
            ->innerJoin('hulpaanbod.project', 'project', 'WITH', 'project.heeftKoppelingen = true')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->leftJoin('hulpaanbod.hulpvraagsoorten', 'hulpvraagsoort') //,'WITH', 'hulpvraagsoort.naam NOT IN (:hulpsoortenZonderKoppelingen)')
            ->leftJoin('hulpaanbod.doelgroepen', 'doelgroep')
            ->innerJoin('izVrijwilliger.intake', 'intake')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'stadsdeel')
            ->leftJoin('vrijwilliger.geslacht', 'geslacht')
//            ->andWhere('hulpvraagsoort.naam NOT IN (:hulpsoortenZonderKoppelingen)')
            ->andWhere('hulpaanbod.startdatum <= :today') // hulpaanbod gestart
            ->andWhere('hulpaanbod.einddatum IS NULL OR hulpaanbod.einddatum >= :today') // hulpaanbod niet afgesloten
            ->andWhere('hulpaanbod.hulpvraag IS NULL') // hulpaanbod niet gekoppeld

            ->andWhere('izVrijwilliger.afsluitDatum IS NULL') // vrijwilliger niet afgesloten
            ->orderBy('hulpaanbod.startdatum', 'ASC')
            ->setParameter('today', new \DateTime('today'))
            //->setParameter('hulpsoortenZonderKoppelingen',$this->hulpsoortenZonderKoppelingen)
        ;

        /**
         * SELECT izk.id, izk.iz_deelnemer_id, SUM(IF (iz_ha_hvs.hulpvraagsoort_id=16, 1, 0)) AS exclude
         * FROM `iz_hulpaanbod_hulpvraagsoort` iz_ha_hvs INNER JOIN iz_koppelingen AS izk ON iz_ha_hvs.hulpaanbod_id = izk.id
         * WHERE iz_ha_hvs.hulpvraagsoort_id = 16
         * GROUP BY iz_ha_hvs.hulpaanbod_id DESC
         */
        $geenDeelnemerMetHulpTimeout = $this->repository->createQueryBuilder('hulpaanbod')
//            ->select('hulpaanbod.id, izVrijwilliger.id), SUM(CASE WHEN hulpvraagsoorten.id = 16 THEN 1 ELSE 0 END) as exclude')
                ->select('hulpaanbod.id, izVrijwilliger.id')
            ->innerJoin('hulpaanbod.hulpvraagsoorten', 'hulpvraagsoorten')
            ->innerJoin('hulpaanbod.izVrijwilliger','izVrijwilliger')

            ->where('hulpvraagsoorten.naam IN(:hulpvraagsoortenZonderKoppelingen)')
            ->groupBy('hulpaanbod.id')
            ->setParameter('hulpvraagsoortenZonderKoppelingen',$this->hulpsoortenZonderKoppelingen)
            ;
        $sql = $geenDeelnemerMetHulpTimeout->getQuery()->getSQL();
        $r = $geenDeelnemerMetHulpTimeout->getQuery()->getResult();
        if(is_array($r) || $r instanceof \Countable ? count($r) : 0) {
            $builder
                ->andWhere('izVrijwilliger NOT IN (:excludeTimeoutDeelnemers)')
                ->setParameter("excludeTimeoutDeelnemers",$r)
                ;
        }
        // hulpaanbod niet gereserveerd
        $gereserveerdeHulpaanbiedingen = $this->repository->createQueryBuilder('hulpaanbod')
            ->innerJoin('hulpaanbod.reserveringen', 'reservering', 'WITH', ':today BETWEEN reservering.startdatum AND reservering.einddatum')
            ->setParameter('today', new \DateTime('today'))
            ->getQuery()
            ->getResult()
        ;
        if (is_array($gereserveerdeHulpaanbiedingen) || $gereserveerdeHulpaanbiedingen instanceof \Countable ? count($gereserveerdeHulpaanbiedingen) : 0) {
            $builder
                ->andWhere('hulpaanbod NOT IN (:gereserveerdeHulpaanbiedingen)')
                ->setParameter('gereserveerdeHulpaanbiedingen', $gereserveerdeHulpaanbiedingen)
            ;
        }

        if ($filter) {
            $filter->applyTo($builder);
        }

        if (!$filter || $filter->matching) {
            // doelgroepen
            if ($hulpvraag->getDoelgroep()) {
                $builder
                    ->andWhere('doelgroep.id IS NULL OR doelgroep = (:doelgroep)')
                    ->setParameter('doelgroep', $hulpvraag->getDoelgroep())
                ;
            }

            // hulpvraagsoorten
            if ($hulpvraag->getHulpvraagsoort()) {
                $builder
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
            $geslacht = $hulpvraag->getIzKlant()->getKlant()->getGeslacht();
            if ($geslacht && Geslacht::AFKORTING_ONBEKEND !== $geslacht->getAfkorting()) {
                $builder
                    ->andWhere('hulpaanbod.voorkeurGeslacht IS NULL OR hulpaanbod.voorkeurGeslacht = :geslacht')
                    ->setParameter('geslacht', $geslacht)
                ;
            }

            // dagdeel
            if ($hulpvraag->getDagdeel()) {
                switch ($hulpvraag->getDagdeel()) {
                    case Hulp::DAGDEEL_OVERDAG:
                        $dagdelen = [Hulp::DAGDEEL_OVERDAG];
                        break;
                    case Hulp::DAGDEEL_AVOND:
                        $dagdelen = [
                            Hulp::DAGDEEL_AVOND,
                            Hulp::DAGDEEL_AVOND_WEEKEND,
                        ];
                        break;
                    case Hulp::DAGDEEL_WEEKEND:
                        $dagdelen = [
                            Hulp::DAGDEEL_WEEKEND,
                            Hulp::DAGDEEL_AVOND_WEEKEND,
                        ];
                        break;
                    case Hulp::DAGDEEL_AVOND_WEEKEND:
                        $dagdelen = [
                            Hulp::DAGDEEL_AVOND,
                            Hulp::DAGDEEL_WEEKEND,
                            Hulp::DAGDEEL_AVOND_WEEKEND,
                        ];
                        break;
                    default:
                        $dagdelen = [
                            Hulp::DAGDEEL_OVERDAG,
                            Hulp::DAGDEEL_AVOND,
                            Hulp::DAGDEEL_WEEKEND,
                            Hulp::DAGDEEL_AVOND_WEEKEND,
                        ];
                        break;
                }
                $builder
                    ->andWhere('hulpaanbod.dagdeel IS NULL OR hulpaanbod.dagdeel IN (:dagdelen)')
                    ->setParameter('dagdelen', $dagdelen)
                ;
            }
        }
//        $sql = $builder->getQuery()->getSQL();

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }

    public function setHulpsoortenZonderKoppelingen($hsZK)
    {
        $this->hulpsoortenZonderKoppelingen = $hsZK;
    }
}
