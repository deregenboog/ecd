<?php

namespace AppBundle\Service;

use AppBundle\Entity\Land;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LandDaoInterface
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
     * @return Land
     */
    public function find($id);

    public function create(Land $land);

    public function update(Land $land);

    public function delete(Land $land);
}
