<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Periode;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface PeriodeDaoInterface
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
     * @return Periode
     */
    public function find($id);

    /**
     * @param Periode $entity
     */
    public function create(Periode $entity);

    /**
     * @param Periode $entity
     */
    public function update(Periode $entity);

    /**
     * @param Periode $entity
     */
    public function delete(Periode $entity);
}
