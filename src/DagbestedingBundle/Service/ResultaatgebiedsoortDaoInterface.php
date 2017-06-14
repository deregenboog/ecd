<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ResultaatgebiedsoortDaoInterface
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
     * @return Resultaatgebiedsoort
     */
    public function find($id);

    /**
     * @param Resultaatgebiedsoort $resultaatgebiedsoort
     */
    public function create(Resultaatgebiedsoort $resultaatgebiedsoort);

    /**
     * @param Resultaatgebiedsoort $resultaatgebiedsoort
     */
    public function update(Resultaatgebiedsoort $resultaatgebiedsoort);

    /**
     * @param Resultaatgebiedsoort $resultaatgebiedsoort
     */
    public function delete(Resultaatgebiedsoort $resultaatgebiedsoort);
}
