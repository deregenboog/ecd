<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Deelname;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DeelnameDaoInterface
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
     * @return Deelname
     */
    public function find($id);

    /**
     * @param Deelname $entity
     */
    public function create(Deelname $entity);

    /**
     * @param Deelname $entity
     */
    public function update(Deelname $entity);

    /**
     * @param Deelname $entity
     */
    public function delete(Deelname $entity);
}
