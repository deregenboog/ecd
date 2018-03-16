<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Huurder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface HuurderDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Huurder
     */
    public function find($id);

    /**
     * @param Huurder $entity
     */
    public function create(Huurder $entity);

    /**
     * @param Huurder $entity
     */
    public function update(Huurder $entity);

    /**
     * @param Huurder $entity
     */
    public function delete(Huurder $entity);

    public function countAangemeld(\DateTime $startdate, \DateTime $enddate);

    public function countGekoppeld(\DateTime $startdate, \DateTime $enddate);

    public function countOntkoppeld(\DateTime $startdate, \DateTime $enddate);

    public function countAfgesloten(\DateTime $startdate, \DateTime $enddate);
}
