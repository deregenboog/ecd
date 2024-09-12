<?php

namespace VillaBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use VillaBundle\Entity\AfsluitredenSlaper;

interface AfsluitredenSlaperDaoInterface
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
     * @return AfsluitredenSlaper
     */
    public function find($id);

    public function create(AfsluitredenSlaper $entity);

    public function update(AfsluitredenSlaper $entity);
}
