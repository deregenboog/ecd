<?php

namespace OekraineBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekraineBundle\Entity\AfsluitredenBezoeker;

interface AfsluitredenBezoekerDaoInterface
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
     * @return AfsluitredenBezoeker
     */
    public function find($id);

    public function create(AfsluitredenBezoeker $entity);

    public function update(AfsluitredenBezoeker $entity);
}
