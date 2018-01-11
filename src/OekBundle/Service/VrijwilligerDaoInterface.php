<?php

namespace OekBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use OekBundle\Entity\Vrijwilliger;
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
     * @return Vrijwilliger
     */
    public function find($id);

    /**
     * @param AppVrijwilliger $vrijwilliger
     *
     * @return Dienstverlener
     */
    public function findOneByVrijwilliger(AppVrijwilliger $vrijwilliger);

    /**
     * @param Vrijwilliger $vrijwilliger
     */
    public function create(Vrijwilliger $vrijwilliger);

    /**
     * @param Vrijwilliger $vrijwilliger
     */
    public function update(Vrijwilliger $vrijwilliger);

    /**
     * @param Vrijwilliger $vrijwilliger
     */
    public function delete(Vrijwilliger $vrijwilliger);

    public function countByStadsdeel(\DateTime $start = null, \DateTime $end = null);

    public function countNewByStadsdeel(\DateTime $start = null, \DateTime $end = null);
}
