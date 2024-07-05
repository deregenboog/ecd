<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Vrijwilliger;
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

    public function findOneByVrijwilliger(AppVrijwilliger $vrijwilliger): ?Vrijwilliger;

    public function create(Vrijwilliger $vrijwilliger);

    public function update(Vrijwilliger $vrijwilliger);

    public function delete(Vrijwilliger $vrijwilliger);

    public function countByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countNewByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);
}
