<?php

namespace OekBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekBundle\Entity\VerwijzingDoor;

interface VerwijzingDoorDaoInterface
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
     * @return VerwijzingDoor
     */
    public function find($id);

    public function create(VerwijzingDoor $entity);

    public function update(VerwijzingDoor $entity);

    public function delete(VerwijzingDoor $entity);
}
