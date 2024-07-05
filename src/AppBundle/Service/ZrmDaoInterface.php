<?php

namespace AppBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Zrm;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ZrmDaoInterface
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
     * @return Klant
     */
    public function find($id);

    public function create(Zrm $entity);

    public function update(Zrm $entity);

    public function delete(Zrm $entity);
}
