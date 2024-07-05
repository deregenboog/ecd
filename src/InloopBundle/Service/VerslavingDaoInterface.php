<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Verslaving;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VerslavingDaoInterface
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
     * @return Verslaving
     */
    public function find($id);

    public function create(Verslaving $entity);

    public function update(Verslaving $entity);

    public function delete(Verslaving $entity);
}
