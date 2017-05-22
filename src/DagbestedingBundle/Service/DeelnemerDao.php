<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Deelnemer;
use AppBundle\Filter\FilterInterface;

class DeelnemerDao extends AbstractDao implements DeelnemerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.werkgebied',
        ],
    ];

    protected $class = Deelnemer::class;

    protected $alias = 'deelnemer';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('deelnemer.klant', 'klant')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
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
}
