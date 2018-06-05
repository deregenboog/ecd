<?php

namespace IzBundle\Service;

use IzBundle\Entity\Hulpvraag;
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
     * @return Hulpvraag
     */
    public function find($id);

    /**
     * @param Hulpvraag $koppeling
     */
    public function create(Hulpvraag $koppeling);

    /**
     * @param Hulpvraag $koppeling
     */
    public function update(Hulpvraag $koppeling);

    /**
     * @param Hulpvraag $koppeling
     */
    public function delete(Hulpvraag $koppeling);
}
