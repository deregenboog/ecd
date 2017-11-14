<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class FactuurDao extends AbstractDao implements FactuurDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'factuur.nummer',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'factuur.nummer',
            'factuur.datum',
            'factuur.bedrag',
            'klant.achternaam',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Factuur::class;

    protected $alias = 'factuur';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, betaling")
            ->innerJoin('factuur.klant', 'klant')
            ->leftJoin('factuur.betalingen', 'betaling')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}.
     */
    public function createBatch(array $entities)
    {
        foreach ($entities as $entity) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    /**
     * {inheritdoc}.
     */
    public function create(Factuur $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Factuur $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Factuur $entity)
    {
        $this->doDelete($entity);
    }
}
