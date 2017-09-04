<?php

namespace HsBundle\Service;

use HsBundle\Entity\Klant;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;

interface KlantDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param AppDateRangeModel $dateRange
     *
     * @return Klant[]
     */
    public function findFacturabel(AppDateRangeModel $dateRange);

    /**
     * @param AppDateRangeModel $dateRange
     *
     * @return int
     */
    public function countFacturabel(AppDateRangeModel $dateRange);

    /**
     * @param int $id
     *
     * @return Klant
     */
    public function find($id);

    /**
     * @param Klant $klant
     */
    public function create(Klant $klant);

    /**
     * @param Klant $klant
     */
    public function update(Klant $klant);

    /**
     * @param Klant $klant
     */
    public function delete(Klant $klant);

    public function countByStadsdeel(\DateTime $start = null, \DateTime $end = null);

    public function countNewByStadsdeel(\DateTime $start = null, \DateTime $end = null);
}
