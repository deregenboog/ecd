<?php

namespace HsBundle\Service;

use HsBundle\Entity\Betaling;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface BetalingDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = 1, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Betaling
     */
    public function find($id);

    /**
     * @param Betaling $betaling
     */
    public function create(Betaling $betaling);

    /**
     * @param Betaling $betaling
     */
    public function update(Betaling $betaling);

    /**
     * @param Betaling $betaling
     */
    public function delete(Betaling $betaling);
}
