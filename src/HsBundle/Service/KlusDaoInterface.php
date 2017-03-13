<?php

namespace HsBundle\Service;

use HsBundle\Entity\Klus;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface KlusDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = 1, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Klus
     */
    public function find($id);

    /**
     * @param Klus $klus
     */
    public function create(Klus $klus);

    /**
     * @param Klus $klus
     */
    public function update(Klus $klus);

    /**
     * @param Klus $klus
     */
    public function delete(Klus $klus);
}
