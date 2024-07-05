<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Klus;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KlusDaoInterface
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
     * @return Klus
     */
    public function find($id);

    public function create(Klus $klus);

    public function update(Klus $klus);

    public function delete(Klus $klus);

    public function countByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);

    public function countDienstverlenersByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countDienstverlenersByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);

    public function countVrijwilligersByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countVrijwilligersByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);
}
