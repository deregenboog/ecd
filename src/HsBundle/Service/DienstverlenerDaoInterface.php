<?php

namespace HsBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Dienstverlener;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DienstverlenerDaoInterface
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
     * @return Dienstverlener
     */
    public function find($id);

    /**
     * @return Dienstverlener
     */
    public function findOneByKlant(Klant $klant);

    public function create(Dienstverlener $dienstverlener);

    public function update(Dienstverlener $dienstverlener);

    public function delete(Dienstverlener $dienstverlener);

    public function countByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);

    public function countNewByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countNewByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);
}
