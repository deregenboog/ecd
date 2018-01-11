<?php

namespace OekBundle\DataFixtures;

use OekBundle\Entity\Aanmelding;
use OekBundle\Entity\VerwijzingDoor;
use Faker\Provider\DateTime;
use OekBundle\Entity\VerwijzingNaar;
use OekBundle\Entity\Afsluiting;
use AppBundle\Entity\Medewerker;

final class OekFakerProvider
{
    public function aanmelding(VerwijzingDoor $verwijzing, Medewerker $medewerker)
    {
        $aanmelding = new Aanmelding();
        $aanmelding
            ->setDatum(DateTime::dateTimeBetween('-5 years', '2017-01-01'))
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker);

        return $aanmelding;
    }

    public function afsluiting(VerwijzingNaar $verwijzing, Medewerker $medewerker)
    {
        $afsluiting = new Afsluiting();
        $afsluiting
            ->setDatum(DateTime::dateTimeBetween('2017-01-01'))
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker);

        return $afsluiting;
    }
}
