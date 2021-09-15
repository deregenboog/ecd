<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\Klant;

interface KlantDaoInterface
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
     * @return Klant
     */
    public function find($id);

    /**
     * @param Klant $entity
     */
    public function create(Klant $entity);

    /**
     * @param Klant $entity
     */
    public function update(Klant $entity);

    /**
     * @param Klant $entity
     */
    public function delete(Klant $entity);

    public function countAangemeld(\DateTime $startdate, \DateTime $enddate);

    public function countGekoppeld(\DateTime $startdate, \DateTime $enddate);

    public function countOntkoppeld(\DateTime $startdate, \DateTime $enddate);

    public function countAfgesloten(\DateTime $startdate, \DateTime $enddate);
}
