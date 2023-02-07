<?php

namespace MwBundle\Service;

use AppBundle\Entity\Medewerker;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\DossierStatus;
use MwBundle\Entity\Klant;

class DossierStatusFactory
{
    public static function getDossierStatus(Klant $klant, Medewerker $medewerker): DossierStatus
    {
        return new Aanmelding($medewerker);
    }
}
