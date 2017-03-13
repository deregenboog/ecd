<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use HsBundle\Entity\Betaling;

class BetalingDao extends AbstractDao implements BetalingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'betaling.datum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'betaling.referentie',
            'betaling.datum',
            'betaling.bedrag',
            'factuur.nummer',
        ],
    ];

    protected $class = Betaling::class;

    protected $alias = 'betaling';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('betaling.factuur', 'factuur')
            ->innerJoin('factuur.klus', 'klus')
            ->innerJoin('klus.klant', 'klant')
            ->innerJoin('klant.klant', 'basisklant')
        ;

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
    public function create(Betaling $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Betaling $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Betaling $entity)
    {
        $this->doDelete($entity);
    }
}
