<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Doelgroep;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DoelgroepDaoInterface
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
     * @return Doelgroep
     */
    public function find($id);

    public function create(Doelgroep $doelgroep);

    public function update(Doelgroep $doelgroep);

    public function delete(Doelgroep $doelgroep);
}
