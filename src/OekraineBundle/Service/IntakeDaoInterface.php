<?php

namespace OekraineBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekraineBundle\Entity\Intake;

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

    /**
     * @return Intake
     */
    public function update(Intake $entity);
}
