<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Dagdeel;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DagdeelDaoInterface
{
    const FASE_BEGINSTAND = 'beginstand';
    const FASE_GESTART = 'gestart';
    const FASE_GESTOPT = 'gestopt';
    const FASE_EINDSTAND = 'eindstand';

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
     * @return Dagdeel
     */
    public function find($id);

    /**
     * @param Dagdeel $dagdeel
     */
    public function create(Dagdeel $dagdeel);

    /**
     * @param Dagdeel $dagdeel
     */
    public function update(Dagdeel $dagdeel);

    /**
     * @param Dagdeel $dagdeel
     */
    public function delete(Dagdeel $dagdeel);

    public function countByDeelnemer(\DateTime $startdate, \DateTime $enddate);
}
