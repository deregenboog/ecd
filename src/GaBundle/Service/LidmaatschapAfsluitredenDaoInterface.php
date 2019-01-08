<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\LidmaatschapAfsluitreden;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LidmaatschapAfsluitredenDaoInterface
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
     * @return LidmaatschapAfsluitreden
     */
    public function find($id);

    /**
     * @param LidmaatschapAfsluitreden $entity
     */
    public function create(LidmaatschapAfsluitreden $entity);

    /**
     * @param LidmaatschapAfsluitreden $entity
     */
    public function update(LidmaatschapAfsluitreden $entity);

    /**
     * @param LidmaatschapAfsluitreden $entity
     */
    public function delete(LidmaatschapAfsluitreden $entity);
}
