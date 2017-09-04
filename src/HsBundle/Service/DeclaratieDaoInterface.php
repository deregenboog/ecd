<?php

namespace HsBundle\Service;

use HsBundle\Entity\Declaratie;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface DeclaratieDaoInterface
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
     * @return Declaratie
     */
    public function find($id);

    /**
     * @param Declaratie $declaratie
     */
    public function create(Declaratie $declaratie);

    /**
     * @param Declaratie $declaratie
     */
    public function update(Declaratie $declaratie);

    /**
     * @param Declaratie $declaratie
     */
    public function delete(Declaratie $declaratie);
}
