<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use MwBundle\Entity\Verslag;

interface VerslagDaoInterface
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
