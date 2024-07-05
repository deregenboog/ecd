<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\Afsluitreden;

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
