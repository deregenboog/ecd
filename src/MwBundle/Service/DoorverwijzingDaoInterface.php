<?php

namespace MwBundle\Service;

use MwBundle\Entity\Doorverwijzing;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DoorverwijzingDaoInterface
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
     * @return Doorverwijzing
     */
    public function find($id);

    /**
     * @param Doorverwijzing $entity
     */
    public function create(Doorverwijzing $entity);

    /**
     * @param Doorverwijzing $entity
     */
    public function update(Doorverwijzing $entity);
}
