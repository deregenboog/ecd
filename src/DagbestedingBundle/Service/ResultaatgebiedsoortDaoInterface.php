<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ResultaatgebiedsoortDaoInterface
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
     * @return Resultaatgebiedsoort
     */
    public function find($id);

    public function create(Resultaatgebiedsoort $resultaatgebiedsoort);

    public function update(Resultaatgebiedsoort $resultaatgebiedsoort);

    public function delete(Resultaatgebiedsoort $resultaatgebiedsoort);
}
