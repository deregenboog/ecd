<?php

namespace IzBundle\Service;

use IzBundle\Entity\BinnengekomenVia;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface BinnengekomenViaDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return BinnengekomenVia
     */
    public function find($id);

    /**
     * @param BinnengekomenVia $entity
     */
    public function create(BinnengekomenVia $entity);

    /**
     * @param BinnengekomenVia $entity
     */
    public function update(BinnengekomenVia $entity);

    /**
     * @param BinnengekomenVia $entity
     */
    public function delete(BinnengekomenVia $entity);
}
