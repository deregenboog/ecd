<?php

namespace ScipBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use ScipBundle\Entity\Verslag;

interface VerslagDaoInterface
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
     * @return Verslag
     */
    public function find($id);

    /**
     * @param Verslag $entity
     */
    public function create(Verslag $entity);

    /**
     * @param Verslag $entity
     */
    public function update(Verslag $entity);

    /**
     * @param Verslag $entity
     */
    public function delete(Verslag $entity);
}
