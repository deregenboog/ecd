<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use MwBundle\Entity\AfsluitredenVrijwilliger;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface AfsluitredenVrijwilligerDaoInterface
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
     * @return AfsluitredenVrijwilliger
     */
    public function find($id);

    /**
     * @param AfsluitredenVrijwilliger $entity
     */
    public function create(AfsluitredenVrijwilliger $entity);

    /**
     * @param AfsluitredenVrijwilliger $entity
     */
    public function update(AfsluitredenVrijwilliger $entity);
}
