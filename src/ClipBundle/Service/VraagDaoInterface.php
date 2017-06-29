<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Vraag;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VraagDaoInterface
{
    const FASE_BEGINSTAND = 'Open aan begin periode';
    const FASE_GESTART = 'Gestart binnen periode';
    const FASE_AFGESLOTEN = 'Afgesloten binnen periode';
    const FASE_EINDSTAND = 'Open aan eind periode';

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
     * @return Vraag
     */
    public function find($id);

    /**
     * @param Vraag $vraag
     */
    public function create(Vraag $vraag);

    /**
     * @param Vraag $vraag
     */
    public function update(Vraag $vraag);

    /**
     * @param Vraag $vraag
     */
    public function delete(Vraag $vraag);

    public function countByCommunicatiekanaal($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByGeboorteland($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByGeslacht($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByHulpvrager($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByLeeftijdscategorie($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByNationaliteit($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByViacategorie($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByVraagsoort($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByWoonplaats($fase, \DateTime $startdate, \DateTime $enddate);
}
