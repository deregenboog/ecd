<?php

namespace BuurtboerderijBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use BuurtboerderijBundle\Entity\Vrijwilliger;
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
     * @return Vrijwilliger
     */
    public function findOneByVrijwilliger(AppVrijwilliger $vrijwilliger);

    public function create(Vrijwilliger $vrijwilliger);

    public function update(Vrijwilliger $vrijwilliger);

    public function delete(Vrijwilliger $vrijwilliger);
}
