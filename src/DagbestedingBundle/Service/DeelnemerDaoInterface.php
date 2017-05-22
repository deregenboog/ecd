<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Deelnemer;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DeelnemerDaoInterface
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
     * @return Deelnemer
     */
    public function find($id);

    /**
     * @param Deelnemer $deelnemer
     */
    public function create(Deelnemer $deelnemer);

    /**
     * @param Deelnemer $deelnemer
     */
    public function update(Deelnemer $deelnemer);

    /**
     * @param Deelnemer $deelnemer
     */
    public function delete(Deelnemer $deelnemer);
}
