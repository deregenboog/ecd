<?php

namespace OekBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekBundle\Entity\Groep;

interface GroepDaoInterface
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
