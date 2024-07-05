<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Verslag;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VerslagDaoInterface
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
     * @return Verslag
     */
    public function find($id);

    public function create(Verslag $entity);

    public function update(Verslag $entity);

    public function delete(Verslag $entity);
}
