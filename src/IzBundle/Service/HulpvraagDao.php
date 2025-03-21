<?php

namespace IzBundle\Service;

use AppBundle\Entity\Geslacht;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Hulp;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Filter\HulpvraagFilter;

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
            'medewerker.voornaam',
            'stadsdeel.naam',
            'geslacht.volledig',
            'hulpvraagsoort.naam',
            'doelgroep.naam',
        ],
        'wrap-queries' => true, // because of ordering by to-many relation
    ];

    protected $class = Hulpvraag::class;

    protected $hulpsoortenZonderKoppelingen = [];

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpvraag')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->leftJoin('izKlant.intake', 'intake')
            ->innerJoin('hulpvraag.project', 'project')
            ->innerJoin('hulpvraag.medewerker', 'medewerker')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort')
            ->innerJoin('hulpvraag.doelgroepen', 'doelgroep')
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

    public function findMatching(Hulpaanbod $hulpaanbod, $page = null, ?HulpvraagFilter $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpvraag')
            ->select('hulpvraag, izKlant, klant')
            ->innerJoin('hulpvraag.project', 'project', 'WITH', 'project.heeftKoppelingen = true')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->leftJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort')
            ->leftJoin('hulpvraag.doelgroepen', 'doelgroep')
            ->innerJoin('izKlant.intake', 'intake')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'stadsdeel')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->andWhere('hulpvraag.startdatum <= :today') // hulpvraag gestart
            ->andWhere('hulpvraag.einddatum IS NULL OR hulpvraag.einddatum >= :today') // hulpvraag niet afgesloten
            ->andWhere('hulpvraag.hulpaanbod IS NULL') // hulpvraag niet gekoppeld
            ->andWhere('izKlant.afsluitDatum IS NULL') // klant niet afgesloten
            ->andWhere('hulpvraagsoort.naam NOT IN (:hulpvraagsoortenZonderKoppelingen)')
            ->orderBy('hulpvraag.startdatum', 'ASC')
            ->setParameter('today', new \DateTime('today'))
            ->setParameter('hulpvraagsoortenZonderKoppelingen', $this->hulpsoortenZonderKoppelingen)
        ;

        // hulpvraag niet gereserveerd
        $gereserveerdeHulpvragen = $this->repository->createQueryBuilder('hulpvraag')
            ->innerJoin('hulpvraag.reserveringen', 'reservering', 'WITH', ':today BETWEEN reservering.startdatum AND reservering.einddatum')
            ->setParameter('today', new \DateTime('today'))
            ->getQuery()
            ->getResult()
        ;
        if (is_array($gereserveerdeHulpvragen) || $gereserveerdeHulpvragen instanceof \Countable ? count($gereserveerdeHulpvragen) : 0) {
            $builder
                ->andWhere('hulpvraag NOT IN (:gereserveerdeHulpvragen)')
                ->setParameter('gereserveerdeHulpvragen', $gereserveerdeHulpvragen)
            ;
        }

        if ($filter) {
            $filter->applyTo($builder);
        }

        if (!$filter || $filter->matching) {
            // doelgroepen
            if ((is_array($hulpaanbod->getDoelgroepen()) || $hulpaanbod->getDoelgroepen() instanceof \Countable ? count($hulpaanbod->getDoelgroepen()) : 0) > 0) {
                $builder
                    ->andWhere('doelgroep.id IS NULL OR doelgroep IN (:doelgroepen)')
                    ->setParameter('doelgroepen', $hulpaanbod->getDoelgroepen())
                ;
            }

            // hulpvraagsoorten
            if ((is_array($hulpaanbod->getHulpvraagsoorten()) || $hulpaanbod->getHulpvraagsoorten() instanceof \Countable ? count($hulpaanbod->getHulpvraagsoorten()) : 0) > 0) {
                $builder
                    ->andWhere('hulpvraagsoort IN (:hulpvraagsoorten)')
                    ->setParameter('hulpvraagsoorten', $hulpaanbod->getHulpvraagsoorten())
                ;
            }

            // expat
            if ($hulpaanbod->isExpat()) {
                $builder->andWhere('hulpvraag.geschiktVoorExpat = true');
            }

            // stagiair
            if ($hulpaanbod->isStagiair()) {
                $builder->andWhere('hulpvraag.stagiair = true');
            }

            // geslacht
            if ($hulpaanbod->getVoorkeurGeslacht()) {
                $builder
                    ->andWhere('geslacht.id IS NULL OR geslacht.afkorting = :onbekend OR geslacht = :voorkeur_geslacht')
                    ->setParameter('onbekend', Geslacht::AFKORTING_ONBEKEND)
                    ->setParameter('voorkeur_geslacht', $hulpaanbod->getVoorkeurGeslacht())
                ;
            }
            $geslacht = $hulpaanbod->getIzVrijwilliger()->getVrijwilliger()->getGeslacht();
            if ($geslacht && Geslacht::AFKORTING_ONBEKEND !== $geslacht->getAfkorting()) {
                $builder
                    ->andWhere('hulpvraag.voorkeurGeslacht IS NULL OR hulpvraag.voorkeurGeslacht = :geslacht')
                    ->setParameter('geslacht', $geslacht)
                ;
            }

            // dagdeel
            if ($hulpaanbod->getDagdeel()) {
                switch ($hulpaanbod->getDagdeel()) {
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
                    ->andWhere('hulpvraag.dagdeel IS NULL OR hulpvraag.dagdeel IN (:dagdelen)')
                    ->setParameter('dagdelen', $dagdelen)
                ;
            }
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }

    public function setHulpsoortenZonderKoppelingen($hsZK)
    {
        $this->hulpsoortenZonderKoppelingen = $hsZK;
    }
}
