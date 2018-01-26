<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Activiteit;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ActiviteitDaoInterface
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
     * @return Activiteit
     */
    public function find($id);

    /**
     * @param Activiteit $entity
     */
    public function create(Activiteit $entity);

    /**
     * @param Activiteit[] $entities
     */
    public function createBatch(array $entities);

    /**
     * @param Activiteit $entity
     */
    public function update(Activiteit $entity);

    /**
     * @param Activiteit $entity
     */
    public function delete(Activiteit $entity);
}
