<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Locatietijd;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LocatietijdDaoInterface
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
     * @return Locatietijd
     */
    public function find($id);

    /**
     * @param Locatietijd $locatietijd
     */
    public function create(Locatietijd $locatietijd);

    /**
     * @param Locatietijd $locatietijd
     */
    public function update(Locatietijd $locatietijd);

    /**
     * @param Locatietijd $locatietijd
     */
    public function delete(Locatietijd $locatietijd);
}
