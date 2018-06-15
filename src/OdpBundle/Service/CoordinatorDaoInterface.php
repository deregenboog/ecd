<?php

namespace OdpBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OdpBundle\Entity\Coordinator;

interface CoordinatorDaoInterface
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
     * @return Coordinator
     */
    public function find($id);

    /**
     * @param Coordinator $coordinator
     */
    public function create(Coordinator $coordinator);

    /**
     * @param Coordinator $coordinator
     */
    public function update(Coordinator $coordinator);

    /**
     * @param Coordinator $coordinator
     */
    public function delete(Coordinator $coordinator);
}
