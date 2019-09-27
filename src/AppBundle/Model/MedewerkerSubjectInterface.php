<?php

namespace AppBundle\Model;

use AppBundle\Entity\Medewerker;

interface MedewerkerSubjectInterface
{
    public function getMedewerker();

    public function setMedewerker(Medewerker $medewerker);
}
