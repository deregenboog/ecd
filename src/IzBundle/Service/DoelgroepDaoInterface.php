<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Doelgroep;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DoelgroepDaoInterface
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
     * @return Doelgroep
     */
    public function find($id);

    /**
     * @param Doelgroep $doelgroep
     */
    public function create(Doelgroep $doelgroep);

    /**
     * @param Doelgroep $doelgroep
     */
    public function update(Doelgroep $doelgroep);

    /**
     * @param Doelgroep $doelgroep
     */
    public function delete(Doelgroep $doelgroep);
}
