<?php

namespace AppBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Zrm;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

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
     * @param Zrm $entity
     */
    public function create(Zrm $entity);

    /**
     * @param Zrm $entity
     */
    public function update(Zrm $entity);

    /**
     * @param Zrm $entity
     */
    public function delete(Zrm $entity);
}
