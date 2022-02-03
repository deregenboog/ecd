<?php

namespace MwBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Aanmelding;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.geboortedatum',
            'geslacht.volledig',
            'medewerker.voornaam',
            'maatschappelijkWerker.voornaam',
            'verslag',
            'gebruikersruimte.naam',
            'laatsteIntakeLocatie.naam',
            'klant.intakedatum',
            'datumLaatsteVerslag',
            'aantalVerslagen',
        ],
//        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        /**
         * @var KlantFilter $filter
         */
        $filter;

        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder
            ->select('klant, laatsteIntake, gebruikersruimte')
            ->addSelect('verslag.datum AS datumLaatsteVerslag')
            ->addSelect('locatie.naam AS laatsteVerslagLocatie')
            ->addSelect('COUNT(DISTINCT verslag.id) AS aantalVerslagen')

            ;
        if($filter->isDirty())
        {
            $builder
                ->leftJoin($this->alias.'.verslagen', 'verslag')
                ->leftJoin('verslag.medewerker','medewerker');
        }
        else
        {
            $builder
                ->join($this->alias.'.verslagen', 'verslag')
                ->join('verslag.medewerker','medewerker');
        }
        $builder
            ->leftJoin('klant.maatschappelijkWerker','maatschappelijkWerker')
            ->leftJoin($this->alias.'.verslagen','v2','WITH','verslag.klant = v2.klant AND (verslag.datum < v2.datum OR (verslag.datum = v2.datum AND verslag.id < v2.id))')
            ->where('v2.id IS NULL')
            ->leftJoin($this->alias.'.geslacht', 'geslacht')
//            ->addSelect('\'2020-07-01\' AS datumLaatsteVerslag')
//            ->addSelect('1 AS aantalVerslagen')
//            ->leftJoin($this->alias.'.huidigeStatus', 'status')
            ->leftJoin($this->alias.'.intakes', 'intake')
            ->leftJoin($this->alias.'.laatsteIntake', 'laatsteIntake')
            ->leftJoin('laatsteIntake.intakelocatie', 'laatsteIntakeLocatie')
            ->leftJoin('verslag.locatie','locatie')
            ->leftJoin($this->alias.'.huidigeMwStatus', 'huidigeMwStatus')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')
            ->groupBy('klant.achternaam, klant.id')
            ;


        if ($filter) {
            $filter->applyTo($builder);
        }

        $sql = SqlExtractor::getFullSQL($builder->getQuery());
        if ($page) {
            /**
             * Vanwege de vele left joins in deze query is de total count niet te optimaliseren (door mij) onder de <900ms.
             * Dat vind ik lang. Temeer omdat in veel gevallen die total count niet bijster interessant is.
             *
             * In de default configuratie van het filter wordt daarom de total count niet geteld. Dat scheelt een eerste snelle klik.
             * Zodra je dan filtert gaat het sowieso sneller en wordt het juiste getal getoont.
             *
             */
            $dql = $builder->getDQL();
            $params = $builder->getParameters();
//            $sql = $builder->getQuery()->getSQL();

            $count = 11111;
            if($filter->isDirty())
            {
                $builder->resetDQLPart("select");
                $builder->select("klant.id");//select only one column for count query.

                $countQuery = $builder->getQuery();
                $fullSql = SqlExtractor::getFullSQL($countQuery);//including bound parameters.

                $countSql = "SELECT COUNT(*) AS count FROM (".$fullSql.") tmp"; //wrap into subquery.
                $countStmt = $this->entityManager->getConnection()->prepare($countSql);
                $countStmt->execute();
                $count = $countStmt->fetchColumn(0);
            }
            $query = $this->entityManager->createQuery($dql)->setHint('knp_paginator.count', $count);

            $query->setParameters($params);
            return $this->paginator->paginate($query, $page, $this->itemsPerPage,$this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function countResultatenPerLocatie($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            /**
             * SELECT DISTINCT (klanten.id), mw_dossier_statussen.class, mw_resultaten.naam FROM klanten LEFT JOIN verslagen ON verslagen.klant_id = klanten.id
            LEFT JOIN mw_dossier_statussen ON mw_dossier_statussen.id = klanten.huidigeMwStatus_id
            LEFT JOIN mw_afsluiting_resultaat ON mw_afsluiting_resultaat.afsluiting_id = mw_dossier_statussen.id
            INNER JOIN mw_resultaten ON mw_resultaten.id = mw_afsluiting_resultaat.resultaat_id
            WHERE verslagen.id IS NOT NULL
            AND mw_dossier_statussen.class IN ('Afsluiting')
             */
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Klant $entity)
    {
        return parent::doCreate($entity);
    }

    public function update(Klant $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Klant $entity)
    {
        $this->doDelete($entity);
    }

}
