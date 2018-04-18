<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Koppelingstatus;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KoppelingstatusDaoInterface
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
     * @return Koppelingstatus
     */
    public function find($id);

    /**
     * @param Koppelingstatus $entity
     */
    public function create(Koppelingstatus $entity);

    /**
     * @param Koppelingstatus $entity
     */
    public function update(Koppelingstatus $entity);

    /**
     * @param Koppelingstatus $entity
     */
    public function delete(Koppelingstatus $entity);
}
