<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Intake;
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
     * @return Intake
     */
    public function update(Intake $entity);
}
