<?php

namespace AppBundle\Service;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface MedewerkerDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param FilterInterface $filter
     *
     * @return int
     */
    public function countAll(FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Medewerker
     */
    public function find($id);

    /**
     * @param Medewerker $entity
     */
    public function create(Medewerker $entity);

    /**
     * @param Medewerker $entity
     */
    public function update(Medewerker $entity);

    /**
     * @param Medewerker $entity
     */
    public function delete(Medewerker $entity);
}
