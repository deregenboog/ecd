<?php

namespace HsBundle\Service;

use HsBundle\Entity\Klant;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface KlantDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = 1, FilterInterface $filter = null);

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
