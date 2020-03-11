<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use MwBundle\Entity\Doorverwijzing;

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

    public function create(Doorverwijzing $entity);

    public function update(Doorverwijzing $entity);
}
