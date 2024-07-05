<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\Verhuurder;

interface VerhuurderDaoInterface
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
     * @return Verhuurder
     */
    public function find($id);

    public function create(Verhuurder $entity);

    public function update(Verhuurder $entity);

    public function delete(Verhuurder $entity);

    public function countAangemeld(\DateTime $startdate, \DateTime $enddate);

    public function countGekoppeld(\DateTime $startdate, \DateTime $enddate);

    public function countOntkoppeld(\DateTime $startdate, \DateTime $enddate);

    public function countAfgesloten(\DateTime $startdate, \DateTime $enddate);
}
