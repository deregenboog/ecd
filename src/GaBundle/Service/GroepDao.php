<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Groep;

class GroepDao extends AbstractDao implements GroepDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'groep.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'groep.naam',
            'groep.startdatum',
            'groep.einddatum',
            'werkgebied.naam',
        ],
    ];

    protected $class = Groep::class;

    protected $alias = 'groep';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, werkgebied, lidmaatschap")
            ->leftJoin("{$this->alias}.lidmaatschappen", 'lidmaatschap')
//             ->leftJoin('lidmaatschap.klant', 'klant')
//             ->leftJoin('lidmaatschap.vrijwilliger', 'vrijwilliger')
            ->leftJoin("{$this->alias}.werkgebied", 'werkgebied')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    public function find($id)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, werkgebied, lidmaatschap")
            ->leftJoin("{$this->alias}.lidmaatschappen", 'lidmaatschap')
            ->leftJoin("{$this->alias}.werkgebied", 'werkgebied')
            ->where("{$this->alias}.id = :id")
            ->setParameter('id', $id)
        ;

        return $builder->getQuery()->getOneOrNullResult();
    }

    public function create(Groep $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Groep $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Groep $entity)
    {
        $this->doDelete($entity);
    }
}
