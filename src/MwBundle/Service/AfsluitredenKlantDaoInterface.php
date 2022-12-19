<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use MwBundle\Entity\AfsluitredenKlant;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface AfsluitredenKlantDaoInterface
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
     * @return AfsluitredenKlant
     */
    public function find($id);

    /**
     * @param AfsluitredenKlant $entity
     */
    public function create(AfsluitredenKlant $entity);

    /**
     * @param AfsluitredenKlant $entity
     */
    public function update(AfsluitredenKlant $entity);
}
