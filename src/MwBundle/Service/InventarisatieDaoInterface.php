<?php

namespace MwBundle\Service;

use InloopBundle\Entity\Locatie;

interface InventarisatieDaoInterface
{
    public function findAllAsTree();

    public function countInventarisaties(
        \DateTime $startdatum,
        \DateTime $einddatum,
        ?Locatie $locatie = null
    );
}
