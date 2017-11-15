<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use ClipBundle\Entity\Vraag;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class VraagDao extends AbstractDao implements VraagDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'vraag.startdatum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'vraag.id',
            'vraag.startdatum',
            'vraag.afsluitdatum',
            'vraagsoort.naam',
            'behandelaar.displayName',
            'client.achternaam',
        ],
    ];

    protected $class = Vraag::class;

    protected $alias = 'vraag';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.soort', 'vraagsoort')
            ->innerJoin($this->alias.'.behandelaar', 'behandelaar')
            ->leftJoin('behandelaar.medewerker', 'medewerker')
            ->innerJoin($this->alias.'.client', 'client')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function findAllOpen($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.soort', 'vraagsoort')
            ->innerJoin($this->alias.'.behandelaar', 'behandelaar')
            ->leftJoin('behandelaar.medewerker', 'medewerker')
            ->innerJoin($this->alias.'.client', 'client')
            ->where("{$this->alias}.afsluitdatum IS NULL OR {$this->alias}.afsluitdatum > :now")
            ->setParameter('now', new \DateTime())
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Vraag $vraag)
    {
        $this->doCreate($vraag);
    }

    public function update(Vraag $vraag)
    {
        $this->doUpdate($vraag);
    }

    public function delete(Vraag $vraag)
    {
        $this->doDelete($vraag);
    }

    public function countByCommunicatiekanaal(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, communicatiekanaal.naam AS groep")
            ->leftJoin("{$this->alias}.communicatiekanaal", 'communicatiekanaal')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByHulpvrager(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, hulpvrager.naam AS groep")
            ->leftJoin("{$this->alias}.hulpvrager", 'hulpvrager')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByLeeftijdscategorie(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, leeftijdscategorie.naam AS groep")
            ->leftJoin("{$this->alias}.leeftijdscategorie", 'leeftijdscategorie')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByEtniciteit(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, client.etniciteit AS groep")
            ->innerJoin("{$this->alias}.client", 'client')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByWoonplaats(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, client.plaats AS groep")
            ->innerJoin("{$this->alias}.client", 'client')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByGeslacht(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, geslacht.volledig AS groep")
            ->innerJoin("{$this->alias}.client", 'client')
            ->leftJoin('client.geslacht', 'geslacht')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByViacategorie(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, viacategorie.naam AS groep")
            ->innerJoin("{$this->alias}.client", 'client')
            ->leftJoin('client.viacategorie', 'viacategorie')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByVraagsoort(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, vraagsoort.naam AS groep")
            ->innerJoin("{$this->alias}.soort", 'vraagsoort')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByMaand(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, DATE_FORMAT(vraag.startdatum, '%M %Y') AS groep")
            ->groupBy('groep')
            ->orderBy('vraag.startdatum')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }


    protected function applyFilter(QueryBuilder $builder, \DateTime $startdate, \DateTime $enddate)
    {
        $builder
            ->andWhere("{$this->alias}.startdatum BETWEEN :startdate AND :enddate")
            ->setParameters([
                'startdate' => $startdate,
                'enddate' => $enddate,
            ])
        ;
    }
}
