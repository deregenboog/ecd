<?php

namespace PfoBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use PfoBundle\Entity\AardRelatie;

interface AardRelatieDaoInterface
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
     * @return AardRelatie
     */
    public function find($id);

    public function create(AardRelatie $entity);

    public function update(AardRelatie $entity);

    public function delete(AardRelatie $entity);
}
