<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Locatietijd;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LocatietijdDaoInterface
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
     * @return Locatietijd
     */
    public function find($id);

    public function create(Locatietijd $locatietijd);

    public function update(Locatietijd $locatietijd);

    public function delete(Locatietijd $locatietijd);
}
