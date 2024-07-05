<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Trajectcoach;

class TrajectcoachDao extends AbstractDao implements TrajectcoachDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'trajectcoach.displayName',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'trajectcoach.id',
            'trajectcoach.displayName',
            'trajectcoach.actief',
        ],
    ];

    protected $class = Trajectcoach::class;

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('trajectcoach')
            ->leftJoin('trajectcoach.medewerker', 'medewerker');

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Trajectcoach $Trajectcoach)
    {
        $this->doCreate($Trajectcoach);
    }

    public function update(Trajectcoach $Trajectcoach)
    {
        $this->doUpdate($Trajectcoach);
    }

    public function delete(Trajectcoach $Trajectcoach)
    {
        $this->doDelete($Trajectcoach);
    }
}
