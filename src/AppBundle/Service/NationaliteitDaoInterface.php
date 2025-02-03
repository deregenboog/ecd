<?php

namespace AppBundle\Service;

use AppBundle\Entity\Nationaliteit;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface NationaliteitDaoInterface
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
     * @return Nationaliteit
     */
    public function find($id);

    public function create(Nationaliteit $nationaliteit);

    public function update(Nationaliteit $nationaliteit);

    public function delete(Nationaliteit $nationaliteit);
}
