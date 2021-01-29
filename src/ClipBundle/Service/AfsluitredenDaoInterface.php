<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Afsluitreden;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface AfsluitredenDaoInterface
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
     * @return Afsluitreden
     */
    public function find($id);

    /**
     * @param Afsluitreden $entity
     */
    public function create(Afsluitreden $entity);

    /**
     * @param Afsluitreden $entity
     */
    public function update(Afsluitreden $entity);

    /**
     * @param Afsluitreden $entity
     */
    public function delete(Afsluitreden $entity);
}
