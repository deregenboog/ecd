<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface FactuurDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = 1, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Factuur
     */
    public function find($id);

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
