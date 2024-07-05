<?php

namespace OekraineBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekraineBundle\Entity\Incident;

interface IncidentDaoInterface
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
