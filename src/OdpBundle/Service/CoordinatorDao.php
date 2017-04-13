<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Coordinator;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

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

    public function findAll($page = 1, FilterInterface $filter = null)
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
