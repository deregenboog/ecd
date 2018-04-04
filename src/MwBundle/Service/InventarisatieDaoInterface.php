<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Entity\Klant;
use InloopBundle\Entity\Locatie;

interface InventarisatieDaoInterface
{
    public function countInventarisaties(
        \DateTime $startdatum,
        \DateTime $einddatum,
        Locatie $locatie = null
    );
}
