<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use MwBundle\Entity\BinnenVia;

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

    public function create(BinnenVia $binnenVia);

    public function update(BinnenVia $binnenVia);

    public function delete(BinnenVia $binnenVia);
}
