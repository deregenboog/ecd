<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Klant;

interface FactuurFactoryInterface
{
    /**
     * @param Klant $klant
     *
     * @return Factuur
     */
    public function create(Klant $klant);
}
