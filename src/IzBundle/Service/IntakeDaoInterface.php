<?php

namespace IzBundle\Service;

use IzBundle\Entity\Intake;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface IntakeDaoInterface
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
     * @return Intake
     */
    public function find($id);

    /**
     * @param Intake $entity
     */
    public function create(Intake $entity);

    /**
     * @param Intake $entity
     */
    public function update(Intake $entity);

    /**
     * @param Intake $entity
     */
    public function delete(Intake $entity);
}
