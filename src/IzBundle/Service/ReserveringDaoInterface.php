<?php

namespace IzBundle\Service;

use IzBundle\Entity\Hulpvraag;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Reservering;

interface ReserveringDaoInterface
{
    /**
     * @param Reservering $entity
     */
    public function create(Reservering $entity);

    /**
     * @param Reservering $entity
     */
    public function update(Reservering $entity);
}
