<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Factuur;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface FactuurDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Factuur
     */
    public function find($id);

    /**
     * @param Factuur[] $facturen
     */
    public function createBatch(array $facturen);

    /**
     * @param Factuur $factuur
     */
    public function create(Factuur $factuur);

    /**
     * @param Factuur $factuur
     */
    public function update(Factuur $factuur);

    /**
     * @param Factuur $factuur
     */
    public function delete(Factuur $factuur);
}
