<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Doelstelling;

class DoelstellingDao extends AbstractDao implements DoelstellingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'project.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'doelstelling.jaar',
            'project.naam',
            'doelstelling.categorie',
            'stadsdeel.naam',
            'doelstelling.aantal',
        ],
    ];

    protected $class = Doelstelling::class;

    protected $alias = 'doelstelling';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', project')
            ->innerJoin($this->alias.'.project', 'project')
            ->leftJoin($this->alias.'.stadsdeel', 'stadsdeel')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Doelstelling $doelstelling)
    {
        $this->doCreate($doelstelling);
    }

    public function update(Doelstelling $doelstelling)
    {
        $this->doUpdate($doelstelling);
    }

    public function delete(Doelstelling $doelstelling)
    {
        $this->doDelete($doelstelling);
    }
}
