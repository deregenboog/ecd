<?php

namespace HsBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Vrijwilliger;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VrijwilligerDaoInterface
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
     * @return Vrijwilliger
     */
    public function find($id);

    /**
     * @return Dienstverlener
     */
    public function findOneByVrijwilliger(AppVrijwilliger $vrijwilliger);

    public function create(Vrijwilliger $vrijwilliger);

    public function update(Vrijwilliger $vrijwilliger);

    public function delete(Vrijwilliger $vrijwilliger);

    public function countByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);

    public function countNewByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countNewByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);
}
