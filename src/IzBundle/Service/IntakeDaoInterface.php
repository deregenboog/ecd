<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Intake;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface IntakeDaoInterface
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
     * @return Intake
     */
    public function find($id);

    public function create(Intake $entity);

    public function update(Intake $entity);

    public function delete(Intake $entity);
}
