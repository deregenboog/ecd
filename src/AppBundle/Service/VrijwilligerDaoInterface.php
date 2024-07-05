<?php

namespace AppBundle\Service;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VrijwilligerDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, ?FilterInterface $filter = null);

    /**
     * @return int
     */
    public function countAll(?FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Vrijwilliger
     */
    public function find($id);

    public function create(Vrijwilliger $vrijwilliger);

    public function update(Vrijwilliger $vrijwilliger);

    public function delete(Vrijwilliger $vrijwilliger);
}
