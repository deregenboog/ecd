<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Memo;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface MemoDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Memo
     */
    public function find($id);

    /**
     * @param Memo $memo
     */
    public function create(Memo $memo);

    /**
     * @param Memo $memo
     */
    public function update(Memo $memo);

    /**
     * @param Memo $memo
     */
    public function delete(Memo $memo);
}
