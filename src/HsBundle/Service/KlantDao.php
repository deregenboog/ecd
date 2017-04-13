<?php

namespace HsBundle\Service;

use HsBundle\Entity\Klant;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'wrap-queries' => true, // because of HAVING clause in filter
        'sortFieldWhitelist' => [
            'klant.actief',
            'klant.id',
            'klant.achternaam',
            'klant.werkgebied',
        ],
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        if ($filter) {
            $filter->applyTo($builder);
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }

    /**
     * {inheritdoc}
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}
     */
    public function create(Klant $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Klant $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Klant $entity)
    {
        $this->doDelete($entity);
    }
}
