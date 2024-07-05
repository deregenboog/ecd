<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Hulpvrager;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface HulpvragerDaoInterface
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
     * @return Hulpvrager
     */
    public function find($id);

    public function create(Hulpvrager $hulpvrager);

    public function update(Hulpvrager $hulpvrager);

    public function delete(Hulpvrager $hulpvrager);
}
