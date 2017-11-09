<?php

namespace HsBundle\Service;

use HsBundle\Entity\Activiteit;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface ActiviteitDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Activiteit
     */
    public function find($id);

    /**
     * @param Activiteit $activiteit
     */
    public function create(Activiteit $activiteit);

    /**
     * @param Activiteit $activiteit
     */
    public function update(Activiteit $activiteit);

    /**
     * @param Activiteit $activiteit
     */
    public function delete(Activiteit $activiteit);
}
