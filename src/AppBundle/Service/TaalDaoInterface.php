<?php

namespace AppBundle\Service;

use AppBundle\Entity\Taal;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TaalDaoInterface
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
     * @return Taal
     */
    public function find($id);

    public function create(Taal $taal);

    public function update(Taal $taal);

    public function delete(Taal $taal);
}
