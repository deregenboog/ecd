<?php

namespace OekBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekBundle\Entity\Training;

interface TrainingDaoInterface
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
     * @return Training
     */
    public function find($id);

    public function create(Training $entity);

    public function update(Training $entity);

    public function delete(Training $entity);
}
