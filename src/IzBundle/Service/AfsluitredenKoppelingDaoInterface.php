<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\AfsluitredenKoppeling;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface AfsluitredenKoppelingDaoInterface
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
     * @return AfsluitredenKoppeling
     */
    public function find($id);

    public function create(AfsluitredenKoppeling $entity);

    public function update(AfsluitredenKoppeling $entity);

    public function delete(AfsluitredenKoppeling $entity);
}
