<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use ClipBundle\Entity\Contactmoment;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;

class ContactmomentDao extends AbstractDao implements ContactmomentDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'contactmoment.datum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'contactmoment.id',
            'vraagsoort.naam+vraag.startdatum',
            'behandelaar.displayName',
            'client.achternaam',
            'contactmoment.datum',
        ],
    ];

    protected $class = Contactmoment::class;

    protected $alias = 'contactmoment';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.behandelaar', 'behandelaar')
            ->leftJoin('behandelaar.medewerker', 'medewerker')
            ->innerJoin($this->alias.'.vraag', 'vraag')
            ->innerJoin('vraag.soort', 'vraagsoort')
            ->innerJoin('vraag.client', 'client')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Contactmoment $contactmoment)
    {
        $this->doCreate($contactmoment);
    }

    public function update(Contactmoment $contactmoment)
    {
        $this->doUpdate($contactmoment);
    }

    public function delete(Contactmoment $contactmoment)
    {
        $this->doDelete($contactmoment);
    }

    public function countByCommunicatiekanaal(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, communicatiekanaal.naam AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->leftJoin('vraag.communicatiekanaal', 'communicatiekanaal')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByHulpvrager(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, hulpvrager.naam AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->leftJoin('vraag.hulpvrager', 'hulpvrager')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByLeeftijdscategorie(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, leeftijdscategorie.naam AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->leftJoin('vraag.leeftijdscategorie', 'leeftijdscategorie')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByEtniciteit(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, client.etniciteit AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.client', 'client')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByWoonplaats(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, client.plaats AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.client', 'client')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByGeslacht(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, geslacht.volledig AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.client', 'client')
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
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.client', 'client')
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
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.soort', 'vraagsoort')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByMaand(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, DATE_FORMAT({$this->alias}.datum, '%M %Y') AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->groupBy('groep')
            ->orderBy("{$this->alias}.datum")
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    protected function applyFilter(QueryBuilder $builder, \DateTime $startdate, \DateTime $enddate)
    {
        $builder
            ->andWhere("{$this->alias}.datum BETWEEN :startdate AND :enddate")
            ->setParameter('startdate', $startdate)
            ->setParameter('enddate', $enddate)
        ;
    }
}
