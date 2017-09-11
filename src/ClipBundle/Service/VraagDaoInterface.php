<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Vraag;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VraagDaoInterface
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

    public function countByCommunicatiekanaal(\DateTime $startdate, \DateTime $enddate);

    public function countByGeboorteland(\DateTime $startdate, \DateTime $enddate);

    public function countByGeslacht(\DateTime $startdate, \DateTime $enddate);

    public function countByHulpvrager(\DateTime $startdate, \DateTime $enddate);

    public function countByLeeftijdscategorie(\DateTime $startdate, \DateTime $enddate);

    public function countByNationaliteit(\DateTime $startdate, \DateTime $enddate);

    public function countByViacategorie(\DateTime $startdate, \DateTime $enddate);

    public function countByVraagsoort(\DateTime $startdate, \DateTime $enddate);

    public function countByWoonplaats(\DateTime $startdate, \DateTime $enddate);
}
