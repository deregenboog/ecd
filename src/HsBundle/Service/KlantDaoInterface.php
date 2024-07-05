<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Klant;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KlantDaoInterface
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
     * @return Klant
     */
    public function find($id);

    public function create(Klant $klant);

    public function update(Klant $klant);

    public function delete(Klant $klant);

    public function countByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);

    public function countNewByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countNewByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);
}
