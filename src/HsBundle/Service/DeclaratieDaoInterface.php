<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Declaratie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DeclaratieDaoInterface
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
     * @return Declaratie
     */
    public function find($id);

    public function create(Declaratie $declaratie);

    public function update(Declaratie $declaratie);

    public function delete(Declaratie $declaratie);
}
