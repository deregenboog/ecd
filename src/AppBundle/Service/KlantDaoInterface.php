<?php

namespace AppBundle\Service;

use AppBundle\Entity\Klant;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface KlantDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param FilterInterface $filter
     *
     * @return int
     */
    public function countAll(FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Klant
     */
    public function find($id);

    /**
     * @param Klant $klant
     */
    public function create(Klant $klant);

    /**
     * @param Klant $klant
     */
    public function update(Klant $klant);

    /**
     * @param Klant $klant
     */
    public function delete(Klant $klant);
}
