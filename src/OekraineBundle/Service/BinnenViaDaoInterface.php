<?php

namespace OekraineBundle\Service;

use AppBundle\Filter\FilterInterface;
use OekraineBundle\Entity\BinnenVia;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface BinnenViaDaoInterface
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
     * @return BinnenVia
     */
    public function find($id);

    public function create(BinnenVia $binnenVia);

    public function update(BinnenVia $binnenVia);

    public function delete(BinnenVia $binnenVia);
}
