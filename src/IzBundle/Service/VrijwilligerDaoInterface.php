<?php

namespace IzBundle\Service;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\IzVrijwilliger;
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
     * @return IzVrijwilliger
     */
    public function find($id);

    /**
     * @return IzVrijwilliger
     */
    public function findOneByVrijwilliger(Vrijwilliger $vrijwilliger);

    public function create(IzVrijwilliger $vrijwilliger);

    public function update(IzVrijwilliger $vrijwilliger);

    public function delete(IzVrijwilliger $vrijwilliger);
}
