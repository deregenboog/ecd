<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Doelstelling;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DoelstellingDaoInterface
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
     * @return Doelstelling
     */
    public function find($id);

    public function create(Doelstelling $doelstelling);

    public function update(Doelstelling $doelstelling);

    public function delete(Doelstelling $doelstelling);
}
