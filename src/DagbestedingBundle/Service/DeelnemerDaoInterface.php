<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Deelnemer;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DeelnemerDaoInterface
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
     * @return Deelnemer
     */
    public function find($id);

    public function create(Deelnemer $deelnemer);

    public function update(Deelnemer $deelnemer);

    public function delete(Deelnemer $deelnemer);

    public function countByBegeleider($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByLocatie($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByProject($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByResultaatgebiedsoort($fase, \DateTime $startdate, \DateTime $enddate);

    public function deelnemersZonderToestemmingsformulier($fase, \DateTime $startdate, \DateTime $enddate);

    public function deelnemersZonderVOG($fase, \DateTime $startdate, \DateTime $enddate);
}
