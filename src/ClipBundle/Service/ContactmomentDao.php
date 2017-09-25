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
            'klant.achternaam',
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
            ->innerJoin('client.klant', 'klant')
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
            ->innerJoin('vraag.communicatiekanaal', 'communicatiekanaal')
            ->groupBy('communicatiekanaal.naam')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByHulpvrager(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, hulpvrager.naam AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.hulpvrager', 'hulpvrager')
            ->groupBy('hulpvrager.naam')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByLeeftijdscategorie(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, leeftijdscategorie.naam AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.leeftijdscategorie', 'leeftijdscategorie')
            ->groupBy('leeftijdscategorie.naam')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByNationaliteit(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, nationaliteit.naam AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.client', 'client')
            ->innerJoin('client.klant', 'klant')
            ->innerJoin('klant.nationaliteit', 'nationaliteit')
            ->groupBy('nationaliteit.naam')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByWoonplaats(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, klant.plaats AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.client', 'client')
            ->innerJoin('client.klant', 'klant')
            ->groupBy('klant.plaats')
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
            ->innerJoin('client.klant', 'klant')
            ->innerJoin('klant.geslacht', 'geslacht')
            ->groupBy('geslacht.volledig')
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
            ->innerJoin('client.viacategorie', 'viacategorie')
            ->groupBy('viacategorie.naam')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByGeboorteland(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, geboorteland.land AS groep")
            ->innerJoin("{$this->alias}.vraag", 'vraag')
            ->innerJoin('vraag.client', 'client')
            ->innerJoin('client.klant', 'klant')
            ->innerJoin('klant.land', 'geboorteland')
            ->groupBy('geboorteland.land')
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
            ->groupBy('vraagsoort.naam')
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
