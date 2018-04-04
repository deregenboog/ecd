<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Entity\Klant;

interface KlantDaoInterface
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
     * @return Klant
     */
    public function find($id);

    /**
     * @param Klant $entity
     */
    public function create(Klant $entity);

    /**
     * @param Klant $entity
     */
    public function update(Klant $entity);

    /**
     * @param Klant $entity
     */
    public function delete(Klant $entity);
}
