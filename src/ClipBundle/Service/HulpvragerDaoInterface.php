<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Hulpvrager;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface HulpvragerDaoInterface
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
     * @return Hulpvrager
     */
    public function find($id);

    /**
     * @param Hulpvrager $hulpvrager
     */
    public function create(Hulpvrager $hulpvrager);

    /**
     * @param Hulpvrager $hulpvrager
     */
    public function update(Hulpvrager $hulpvrager);

    /**
     * @param Hulpvrager $hulpvrager
     */
    public function delete(Hulpvrager $hulpvrager);
}
