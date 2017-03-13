<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Coordinator;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CoordinatorDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = 1);

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
