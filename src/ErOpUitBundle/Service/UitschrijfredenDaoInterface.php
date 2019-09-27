<?php

namespace ErOpUitBundle\Service;

use AppBundle\Filter\FilterInterface;
use ErOpUitBundle\Entity\Uitschrijfreden;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface UitschrijfredenDaoInterface
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
     * @return Uitschrijfreden
     */
    public function find($id);

    /**
     * @param Uitschrijfreden $entity
     */
    public function create(Uitschrijfreden $entity);

    /**
     * @param Uitschrijfreden $entity
     */
    public function update(Uitschrijfreden $entity);

    /**
     * @param Uitschrijfreden $entity
     */
    public function delete(Uitschrijfreden $entity);
}
