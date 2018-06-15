<?php

namespace MwBundle\Service;

use InloopBundle\Entity\Locatie;

interface InventarisatieDaoInterface
{
    public function countInventarisaties(
        \DateTime $startdatum,
        \DateTime $einddatum,
        Locatie $locatie = null
    );
}
