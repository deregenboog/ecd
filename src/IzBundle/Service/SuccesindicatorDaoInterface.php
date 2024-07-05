<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Succesindicator;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface SuccesindicatorDaoInterface
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
     * @return Succesindicator
     */
    public function find($id);

    public function create(Succesindicator $entity);

    public function update(Succesindicator $entity);

    public function delete(Succesindicator $entity);
}
