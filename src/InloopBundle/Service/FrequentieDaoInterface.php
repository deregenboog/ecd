<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Frequentie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface FrequentieDaoInterface
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
     * @return Frequentie
     */
    public function find($id);

    /**
     * @param Frequentie $entity
     */
    public function create(Frequentie $entity);

    /**
     * @param Frequentie $entity
     */
    public function update(Frequentie $entity);

    /**
     * @param Frequentie $entity
     */
    public function delete(Frequentie $entity);
}
