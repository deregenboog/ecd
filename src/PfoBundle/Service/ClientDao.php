<?php

namespace PfoBundle\Service;

use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use PfoBundle\Entity\Client;

class ClientDao extends AbstractDao implements ClientDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'client.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'client.voornaam',
            'client.achternaam',
            'groep.naam',
            'medewerker.achternaam',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Client::class;

    protected $alias = 'client';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, groep")
            ->innerJoin("{$this->alias}.groep", 'groep')
            ->innerJoin("{$this->alias}.medewerker", 'medewerker')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function create(Client $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Client $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Client $entity)
    {
        $this->doDelete($entity);
    }
}
