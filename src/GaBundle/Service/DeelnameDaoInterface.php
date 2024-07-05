<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Deelname;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DeelnameDaoInterface
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
     * @return Deelname
     */
    public function find($id);

    public function create(Deelname $entity);

    public function update(Deelname $entity);

    public function delete(Deelname $entity);
}
