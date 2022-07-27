<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use DagbestedingBundle\Entity\Werkdoel;

interface WerkdoelDaoInterface
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
     * @return Werkdoel
     */
    public function find($id);

    /**
     * @param Werkdoel $entity
     */
    public function create(Werkdoel $entity);

    /**
     * @param Werkdoel $entity
     */
    public function update(Werkdoel $entity);

    /**
     * @param Werkdoel $entity
     */
    public function delete(Werkdoel $entity);
}
