<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Client;

class ClientDao extends AbstractDao implements ClientDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.werkgebied',
            'client.aanmelddatum',
        ],
    ];

    protected $class = Client::class;

    protected $alias = 'client';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant")
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

    public function create(Client $client)
    {
        $this->doCreate($client);
    }

    public function update(Client $client)
    {
        $this->doUpdate($client);
    }

    public function delete(Client $client)
    {
        $this->doDelete($client);
    }
}
