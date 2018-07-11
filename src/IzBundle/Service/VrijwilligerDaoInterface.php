<?php

namespace IzBundle\Service;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\IzVrijwilliger;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VrijwilligerDaoInterface
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
     * @return IzVrijwilliger
     */
    public function find($id);

    /**
     * @param Vrijwilliger $vrijwilliger
     *
     * @return IzVrijwilliger
     */
    public function findOneByVrijwilliger(Vrijwilliger $vrijwilliger);

    /**
     * @param IzVrijwilliger $vrijwilliger
     */
    public function create(IzVrijwilliger $vrijwilliger);

    /**
     * @param IzVrijwilliger $vrijwilliger
     */
    public function update(IzVrijwilliger $vrijwilliger);

    /**
     * @param IzVrijwilliger $vrijwilliger
     */
    public function delete(IzVrijwilliger $vrijwilliger);
}
