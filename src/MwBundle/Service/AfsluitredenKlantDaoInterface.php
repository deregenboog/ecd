<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use MwBundle\Entity\AfsluitredenKlant;

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

    public function create(AfsluitredenKlant $entity);

    public function update(AfsluitredenKlant $entity);
}
