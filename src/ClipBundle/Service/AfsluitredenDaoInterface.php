<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Afsluitreden;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface AfsluitredenDaoInterface
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
     * @return Afsluitreden
     */
    public function find($id);

    public function create(Afsluitreden $entity);

    public function update(Afsluitreden $entity);

    public function delete(Afsluitreden $entity);
}
