<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Herinnering;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface HerinneringDaoInterface
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
     * @return Herinnering
     */
    public function find($id);

    /**
     * @param Herinnering $herinnering
     */
    public function create(Herinnering $herinnering);

    /**
     * @param Herinnering $herinnering
     */
    public function update(Herinnering $herinnering);

    /**
     * @param Herinnering $herinnering
     */
    public function delete(Herinnering $herinnering);
}
