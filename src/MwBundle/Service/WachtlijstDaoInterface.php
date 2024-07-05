<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Intake;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface WachtlijstDaoInterface
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

    /**
     * @return Intake
     */
    public function update(Intake $entity);
}
