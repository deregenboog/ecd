<?php

namespace DagbestedingBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Entity\Document;
use Doctrine\ORM\QueryBuilder;

class DeelnemerDao extends AbstractDao implements DeelnemerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'werkgebied.naam',
            'deelnemer.aanmelddatum',
            'deelnemer.afsluitdatum',
            'medewerker.voornaam',
        ],
    ];

    protected $class = Deelnemer::class;

    protected $alias = 'deelnemer';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('deelnemer.klant', 'klant')
            ->leftJoin('deelnemer.medewerker', 'medewerker')
            ->leftJoin('klant.werkgebied', 'werkgebied')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * @return Deelnemer
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
    }

    public function create(Deelnemer $deelnemer)
    {
        $this->doCreate($deelnemer);
    }

    public function update(Deelnemer $deelnemer)
    {
        $this->doUpdate($deelnemer);
    }

    public function delete(Deelnemer $deelnemer)
    {
        $this->doDelete($deelnemer);
    }

    public function countByBegeleider($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT('.$this->alias.') AS aantal, trajectcoach.displayName AS groep')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->innerJoin('traject.trajectcoach', 'trajectcoach')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByLocatie($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT('.$this->alias.') AS aantal, locatie.naam AS groep')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->innerJoin('traject.locaties', 'locatie')
            ->groupBy('locatie.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByProject($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT('.$this->alias.') AS aantal, project.naam AS groep')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->innerJoin('traject.deelnames', 'deelnames')
            ->innerJoin('deelnames.project', 'project')
            ->groupBy('project.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByResultaatgebiedsoort($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT('.$this->alias.') AS aantal, soort.naam AS groep')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->innerJoin('traject.resultaatgebied', 'resultaatgebied')
            ->innerJoin('resultaatgebied.soort', 'soort')
            ->groupBy('soort.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function deelnemersZonderVOG($fase, \DateTime $startdate, \DateTime $enddate)
    {
        /**
         * SELECT ddd.deelnemer_id FROM dagbesteding_documenten ddoc
         * INNER JOIN dagbesteding_deelnemer_document ddd on ddoc.id = ddd.document_id
         * WHERE ddoc.naam = 'VOG'
         * GROUP BY ddd.deelnemer_id.
         *
         * -- Eerst lijstje met iedereen met VOG maken
         */
        $docBuilder = $this->repository->createQueryBuilder('deelnemer');
        $docBuilder->select('deelnemer.id')
            ->innerJoin('deelnemer.documenten', 'documenten')
            ->innerJoin('deelnemer.klant', 'klant')
            ->where("documenten.naam = 'VOG'")
            ->groupBy('deelnemer.id')

        ;
        $res = $docBuilder->getQuery()->getResult();
        $deelnemersMetVog = [];
        foreach ($res as $v) {
            $deelnemersMetVog[] = $v['id'];
        }
        /**
         * Basis:
         *
         * SELECT dd.id FROM dagbesteding_deelnemers dd LEFT JOIN dagbesteding_deelnemer_document ddd on dd.id = ddd.deelnemer_id
         * LEFT JOIN dagbesteding_documenten ddoc on ddd.document_id = ddoc.id
         * WHERE ddd.deelnemer_id IS NULL
         * AND NOT IN  ()...
         * GROUP BY dd.id
         *
         * -- lijstje gebruiken om eruit te filteren.
         */
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.".id AS id, CONCAT_WS(' ',klant.voornaam, klant.tussenvoegsel, klant.achternaam) AS naam,
            project.naam AS projectNaam")
            ->leftJoin($this->alias.'.documenten', 'documenten')
            ->innerJoin($this->alias.'.klant', 'klant')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->leftJoin('traject.deelnames', 'deelnames')
            ->leftJoin('deelnames.project', 'project')
            ->where('documenten IS NULL')
            ->andWhere($this->alias.'.id NOT IN (:deelnemersMetVog)')
            ->groupBy($this->alias.'.id')
            ->orderBy('klant.achternaam', 'ASC')
            ->setParameter('deelnemersMetVog', $deelnemersMetVog)
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);
        $q = $builder->getQuery()->getSQL();

        return $builder->getQuery()->getResult();
    }

    public function deelnemersZonderToestemmingsformulier($fase, \DateTime $startdate, \DateTime $enddate)
    {
        /**
         * SELECT ddd.deelnemer_id FROM dagbesteding_documenten ddoc
         * INNER JOIN dagbesteding_deelnemer_document ddd on ddoc.id = ddd.document_id
         * WHERE ddoc.naam = 'VOG'
         * GROUP BY ddd.deelnemer_id.
         *
         * -- Eerst lijstje met iedereen met VOG maken
         */
        $docBuilder = $this->repository->createQueryBuilder('deelnemer');
        $docBuilder->select('deelnemer.id')
            ->innerJoin('deelnemer.documenten', 'documenten')
            ->where("documenten.naam LIKE '%oestemmin%'")
            ->groupBy('deelnemer.id');
        $res = $docBuilder->getQuery()->getResult();
        $deelnemersMet = [];
        foreach ($res as $v) {
            $deelnemersMet[] = $v['id'];
        }
        /**
         * Basis:
         *
         * SELECT dd.id FROM dagbesteding_deelnemers dd LEFT JOIN dagbesteding_deelnemer_document ddd on dd.id = ddd.deelnemer_id
         * LEFT JOIN dagbesteding_documenten ddoc on ddd.document_id = ddoc.id
         * WHERE ddd.deelnemer_id IS NULL
         * AND NOT IN  ()...
         * GROUP BY dd.id
         *
         * -- lijstje gebruiken om eruit te filteren.
         */
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.".id AS id, CONCAT_WS(' ',klant.voornaam, klant.tussenvoegsel, klant.achternaam) AS naam,
            project.naam AS projectNaam")

            ->leftJoin($this->alias.'.documenten', 'documenten')

            ->innerJoin($this->alias.'.klant', 'klant')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->leftJoin('traject.deelnames', 'deelnames')
            ->leftJoin('deelnames.project', 'project')
            ->where('documenten IS NULL')
            ->andWhere($this->alias.'.id NOT IN (:deelnemersMet)')
            ->groupBy($this->alias.'.id')
            ->orderBy('klant.achternaam', 'ASC')
            ->setParameter('deelnemersMet', $deelnemersMet)
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);
        //        $q = $builder->getQuery()->getSQL();

        return $builder->getQuery()->getResult();
    }

    protected function applyFilter(QueryBuilder $builder, $fase, \DateTime $startdate, \DateTime $enddate)
    {
        switch ($fase) {
            case self::FASE_BEGINSTAND:
                $builder
                    ->andWhere('traject.startdatum < :startdate')
                    ->andWhere('traject.einddatum IS NULL OR traject.einddatum >= :startdate')
                    ->setParameter('startdate', $startdate)
                ;
                break;
            case self::FASE_GESTART:
                $builder
                    ->andWhere('traject.startdatum BETWEEN :startdate AND :enddate')
                    ->setParameter('startdate', $startdate)
                    ->setParameter('enddate', $enddate)

                ;
                break;
            case self::FASE_GESTOPT:
                $builder
                    ->andWhere('traject.einddatum BETWEEN :startdate AND :enddate')
                    ->setParameter('startdate', $startdate)
                    ->setParameter('enddate', $enddate)
                ;
                break;
            case self::FASE_EINDSTAND:
                $builder
                    ->andWhere('traject.startdatum < :enddate')
                    ->andWhere('traject.einddatum IS NULL OR traject.einddatum > :enddate')
                    ->setParameter('enddate', $enddate)
                ;
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Ongeldige fase "%s"', $fase));
        }
    }
}
