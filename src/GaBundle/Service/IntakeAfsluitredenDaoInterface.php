<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\IntakeAfsluitreden;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface IntakeAfsluitredenDaoInterface
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
     * @return IntakeAfsluitreden
     */
    public function find($id);

    /**
     * @param IntakeAfsluitreden $entity
     */
    public function create(IntakeAfsluitreden $entity);

    /**
     * @param IntakeAfsluitreden $entity
     */
    public function update(IntakeAfsluitreden $entity);

    /**
     * @param IntakeAfsluitreden $entity
     */
    public function delete(IntakeAfsluitreden $entity);
}
