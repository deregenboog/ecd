<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Werkdoel;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface WerkdoelDaoInterface
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
     * @return Werkdoel
     */
    public function find($id);

    public function create(Werkdoel $entity);

    public function update(Werkdoel $entity);

    public function delete(Werkdoel $entity);
}
