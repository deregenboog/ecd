<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\AfsluitredenKoppeling;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface AfsluitredenKoppelingDaoInterface
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
     * @return AfsluitredenKoppeling
     */
    public function find($id);

    /**
     * @param AfsluitredenKoppeling $entity
     */
    public function create(AfsluitredenKoppeling $entity);

    /**
     * @param AfsluitredenKoppeling $entity
     */
    public function update(AfsluitredenKoppeling $entity);

    /**
     * @param AfsluitredenKoppeling $entity
     */
    public function delete(AfsluitredenKoppeling $entity);
}
