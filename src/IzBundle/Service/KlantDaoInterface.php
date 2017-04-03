<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzKlant;
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
     * @param int $id
     *
     * @return IzKlant
     */
    public function find($id);

    /**
     * @param IzKlant $klant
     */
    public function create(IzKlant $klant);

    /**
     * @param IzKlant $klant
     */
    public function update(IzKlant $klant);

    /**
     * @param IzKlant $klant
     */
    public function delete(IzKlant $klant);
}
