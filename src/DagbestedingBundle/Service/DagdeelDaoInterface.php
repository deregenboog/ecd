<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Dagdeel;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DagdeelDaoInterface
{
    public const FASE_BEGINSTAND = 'beginstand';
    public const FASE_GESTART = 'gestart';
    public const FASE_GESTOPT = 'gestopt';
    public const FASE_EINDSTAND = 'eindstand';

    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, ?FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Dagdeel
     */
    public function find($id);

    public function create(Dagdeel $dagdeel);

    public function update(Dagdeel $dagdeel);

    public function delete(Dagdeel $dagdeel);

    public function countByDeelnemer(\DateTime $startdate, \DateTime $enddate);
}
