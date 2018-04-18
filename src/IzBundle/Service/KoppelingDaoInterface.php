<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Koppeling;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KoppelingDaoInterface
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
     * @return IzKlant
     */
    public function find($id);

    /**
     * @param Koppeling $koppeling
     */
    public function create(Koppeling $koppeling);

    /**
     * @param Koppeling $koppeling
     */
    public function update(Koppeling $koppeling);

    /**
     * @param Koppeling $koppeling
     */
    public function delete(Koppeling $koppeling);
}
