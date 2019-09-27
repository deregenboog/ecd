<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use ClipBundle\Entity\Client;

class ClientDao extends AbstractDao implements ClientDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'client.id',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'client.id',
            'client.achternaam',
            'werkgebied.naam',
            'client.aanmelddatum',
        ],
    ];

    protected $class = Client::class;

    protected $alias = 'client';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->leftJoin($this->alias.'.werkgebied', 'werkgebied')
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
