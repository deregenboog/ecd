<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Contactmoment;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ContactmomentDaoInterface
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
     * @return Contactmoment
     */
    public function find($id);

    public function create(Contactmoment $contactmoment);

    public function update(Contactmoment $contactmoment);

    public function delete(Contactmoment $contactmoment);

    public function countByCommunicatiekanaal(\DateTime $startdate, \DateTime $enddate);

    public function countByEtniciteit(\DateTime $startdate, \DateTime $enddate);

    public function countByGeslacht(\DateTime $startdate, \DateTime $enddate);

    public function countByHulpvrager(\DateTime $startdate, \DateTime $enddate);

    public function countByLeeftijdscategorie(\DateTime $startdate, \DateTime $enddate);

    public function countByMaand(\DateTime $startdate, \DateTime $enddate);

    public function countByStadsdeel(\DateTime $startdate, \DateTime $enddate);

    public function countByViacategorie(\DateTime $startdate, \DateTime $enddate);

    public function countByVraagsoort(\DateTime $startdate, \DateTime $enddate);

    public function countByWoonplaats(\DateTime $startdate, \DateTime $enddate);
}
