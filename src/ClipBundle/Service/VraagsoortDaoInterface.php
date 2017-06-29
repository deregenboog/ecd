<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Vraagsoort;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VraagsoortDaoInterface
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
     * @return Vraagsoort
     */
    public function find($id);

    /**
     * @param Vraagsoort $vraagsoort
     */
    public function create(Vraagsoort $vraagsoort);

    /**
     * @param Vraagsoort $vraagsoort
     */
    public function update(Vraagsoort $vraagsoort);

    /**
     * @param Vraagsoort $vraagsoort
     */
    public function delete(Vraagsoort $vraagsoort);
}
