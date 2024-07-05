<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Groep;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface GroepDaoInterface
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
     * @return Groep
     */
    public function find($id);

    public function create(Groep $entity);

    public function update(Groep $entity);

    public function delete(Groep $entity);
}
