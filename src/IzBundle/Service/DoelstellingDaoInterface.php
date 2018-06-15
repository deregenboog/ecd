<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Doelstelling;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DoelstellingDaoInterface
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
     * @return Doelstelling
     */
    public function find($id);

    /**
     * @param Doelstelling $doelstelling
     */
    public function create(Doelstelling $doelstelling);

    /**
     * @param Doelstelling $doelstelling
     */
    public function update(Doelstelling $doelstelling);

    /**
     * @param Doelstelling $doelstelling
     */
    public function delete(Doelstelling $doelstelling);
}
