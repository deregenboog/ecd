<?php

namespace ScipBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use ScipBundle\Entity\Label;

interface LabelDaoInterface
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
     * @return Label
     */
    public function find($id);

    public function create(Label $entity);

    public function update(Label $entity);

    public function delete(Label $entity);
}
