<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\BinnenVia;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface BinnenViaDaoInterface
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
     * @return BinnenVia
     */
    public function find($id);

    /**
     * @param BinnenVia $binnenVia
     */
    public function create(BinnenVia $binnenVia);

    /**
     * @param BinnenVia $binnenVia
     */
    public function update(BinnenVia $binnenVia);

    /**
     * @param BinnenVia $binnenVia
     */
    public function delete(BinnenVia $binnenVia);
}
