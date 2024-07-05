<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\Coordinator;

interface CoordinatorDaoInterface
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
     * @return Coordinator
     */
    public function find($id);

    public function create(Coordinator $coordinator);

    public function update(Coordinator $coordinator);

    public function delete(Coordinator $coordinator);
}
