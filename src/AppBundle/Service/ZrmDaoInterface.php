<?php

namespace AppBundle\Service;

use AppBundle\Entity\Klant;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Zrm;

interface ZrmDaoInterface
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
     * @return Klant
     */
    public function find($id);

    /**
     * @param Klant $klant
     */
    public function create(Zrm $entity);

    /**
     * @param Klant $klant
     */
    public function update(Zrm $entity);

    /**
     * @param Klant $klant
     */
    public function delete(Zrm $entity);
}
