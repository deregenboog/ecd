<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Behandelaar;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface BehandelaarDaoInterface
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
     * @return Behandelaar
     */
    public function find($id);

    public function create(Behandelaar $behandelaar);

    public function update(Behandelaar $behandelaar);

    public function delete(Behandelaar $behandelaar);
}
