<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Hulpvraag;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KoppelingDaoInterface
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
     * @return Hulpvraag
     */
    public function find($id);

    public function create(Hulpvraag $koppeling);

    public function update(Hulpvraag $koppeling);

    public function delete(Hulpvraag $koppeling);
}
