<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Groep;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface GroepDaoInterface
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
     * @return Groep
     */
    public function find($id);

    /**
     * @param Groep $entity
     */
    public function create(Groep $entity);

    /**
     * @param Groep $entity
     */
    public function update(Groep $entity);

    /**
     * @param Groep $entity
     */
    public function delete(Groep $entity);
}
