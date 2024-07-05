<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\BinnengekomenVia;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface BinnengekomenViaDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, ?FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return BinnengekomenVia
     */
    public function find($id);

    public function create(BinnengekomenVia $entity);

    public function update(BinnengekomenVia $entity);

    public function delete(BinnengekomenVia $entity);
}
