<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Behandelaar;

class BehandelaarDao extends AbstractDao implements BehandelaarDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'behandelaar.displayName',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'behandelaar.id',
            'behandelaar.displayName',
            'behandelaar.actief',
        ],
    ];

    protected $class = Behandelaar::class;

    protected $alias = 'behandelaar';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias, 'medewerker')
            ->leftJoin($this->alias.'.medewerker', 'medewerker')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Behandelaar $behandelaar)
    {
        $this->doCreate($behandelaar);
    }

    public function update(Behandelaar $behandelaar)
    {
        $this->doUpdate($behandelaar);
    }

    public function delete(Behandelaar $behandelaar)
    {
        $this->doDelete($behandelaar);
    }
}
