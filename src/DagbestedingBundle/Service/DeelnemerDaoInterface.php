<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Deelnemer;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DeelnemerDaoInterface
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

    public function countByBegeleider($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByLocatie($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByProject($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByResultaatgebiedsoort($fase, \DateTime $startdate, \DateTime $enddate);
}
