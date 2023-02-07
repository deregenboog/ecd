<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use MwBundle\Entity\AfsluitredenVrijwilliger;

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

    public function create(AfsluitredenVrijwilliger $entity);

    public function update(AfsluitredenVrijwilliger $entity);
}
