<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzHulpvraag;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

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
     * @param IzKlant $koppeling
     */
    public function create(IzHulpvraag $koppeling);

    /**
     * @param IzKlant $koppeling
     */
    public function update(IzHulpvraag $koppeling);

    /**
     * @param IzKlant $koppeling
     */
    public function delete(IzHulpvraag $koppeling);
}
