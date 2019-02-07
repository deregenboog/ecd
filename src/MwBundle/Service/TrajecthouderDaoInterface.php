<?php

namespace MwBundle\Service;

use MwBundle\Entity\Trajecthouder;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TrajecthouderDaoInterface
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
     * @return Trajecthouder
     */
    public function find($id);

    /**
     * @param Trajecthouder $entity
     */
    public function create(Trajecthouder $entity);

    /**
     * @param Trajecthouder $entity
     */
    public function update(Trajecthouder $entity);
}
