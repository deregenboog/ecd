<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\VerwijzingDoor;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VerwijzingDoorDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return VerwijzingDoor
     */
    public function find($id);

    /**
     * @param VerwijzingDoor $entity
     */
    public function create(VerwijzingDoor $entity);

    /**
     * @param VerwijzingDoor $entity
     */
    public function update(VerwijzingDoor $entity);

    /**
     * @param VerwijzingDoor $entity
     */
    public function delete(VerwijzingDoor $entity);
}
