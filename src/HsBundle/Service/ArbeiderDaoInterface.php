<?php

namespace HsBundle\Service;

use HsBundle\Entity\Arbeider;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface ArbeiderDaoInterface
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
     * @return Arbeider
     */
    public function find($id);

    /**
     * @param Arbeider $arbeider
     */
    public function create(Arbeider $arbeider);

    /**
     * @param Arbeider $arbeider
     */
    public function update(Arbeider $arbeider);

    /**
     * @param Arbeider $arbeider
     */
    public function delete(Arbeider $arbeider);
}
