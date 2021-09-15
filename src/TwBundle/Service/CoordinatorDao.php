<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use TwBundle\Entity\Coordinator;

class CoordinatorDao extends AbstractDao implements CoordinatorDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'medewerker.voornaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'coordinator.id',
            'medewerker.voornaam',
        ],
    ];

    protected $class = Coordinator::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('coordinator')
            ->innerJoin('coordinator.medewerker', 'medewerker');

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }

    public function create(Coordinator $coordinator)
    {
        $this->doCreate($coordinator);
    }

    public function update(Coordinator $coordinator)
    {
        $this->doUpdate($coordinator);
    }

    public function delete(Coordinator $coordinator)
    {
        $this->doDelete($coordinator);
    }
}
