<?php

namespace AppBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Entity\Medewerker;

interface MedewerkerDaoInterface
{
    /**
     * @param string $username
     *
     * @return Medewerker
     */
    public function find($username);
}
