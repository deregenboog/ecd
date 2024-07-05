<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Vraagsoort;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VraagsoortDaoInterface
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
     * @return Vraagsoort
     */
    public function find($id);

    public function create(Vraagsoort $vraagsoort);

    public function update(Vraagsoort $vraagsoort);

    public function delete(Vraagsoort $vraagsoort);
}
