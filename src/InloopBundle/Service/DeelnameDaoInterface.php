<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekBundle\Entity\Training;

interface DeelnameDaoInterface
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
     * @return Training
     */
    public function find($id);

    /**
     * @param Training $entity
     */
    public function create(Training $entity);

    /**
     * @param Training $entity
     */
    public function update(Training $entity);

    /**
     * @param Training $entity
     */
    public function delete(Training $entity);
}
