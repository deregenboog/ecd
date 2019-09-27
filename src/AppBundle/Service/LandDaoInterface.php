<?php

namespace AppBundle\Service;

use AppBundle\Entity\Land;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LandDaoInterface
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
     * @return Land
     */
    public function find($id);

    /**
     * @param Land $land
     */
    public function create(Land $land);

    /**
     * @param Land $land
     */
    public function update(Land $land);

    /**
     * @param Land $land
     */
    public function delete(Land $land);
}
