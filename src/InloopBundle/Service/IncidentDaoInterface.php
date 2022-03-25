<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Incident;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface IncidentDaoInterface
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
     * @return Incident
     */
    public function find($id);

    /**
     * @param Incident
     *
     * @return Incident
     */
    public function create(Incident $entity);

    /**
     * @param Incident
     *
     * @return Incident
     */
    public function update(Incident $entity);

    /**
     * @param Incident
     *
     * @return Incident
     */
    public function delete(Incident $entity);
}
