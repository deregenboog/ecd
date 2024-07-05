<?php

namespace OekBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekBundle\Entity\Memo;

interface MemoDaoInterface
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
     * @return Memo
     */
    public function find($id);

    public function create(Memo $memo);

    public function update(Memo $memo);

    public function delete(Memo $memo);
}
