<?php

namespace PfoBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use PfoBundle\Entity\Client;

interface ClientDaoInterface
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
     * @return Client
     */
    public function find($id);

    public function create(Client $entity);

    public function update(Client $entity);

    public function delete(Client $entity);
}
