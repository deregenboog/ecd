<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Traject;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TrajectDaoInterface
{
    public const FASE_BEGINSTAND = 'beginstand';
    public const FASE_GESTART = 'gestart';
    public const FASE_GESTOPT = 'gestopt';
    public const FASE_EINDSTAND = 'eindstand';

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
     * @return Traject
     */
    public function find($id);

    /**
     * @param Traject $traject
     */
    public function create(Traject $traject);

    /**
     * @param Traject $traject
     */
    public function update(Traject $traject);

    /**
     * @param Traject $traject
     */
    public function delete(Traject $traject);

    public function countByAfsluiting($fase, \DateTime $startdate, \DateTime $enddate);
}
