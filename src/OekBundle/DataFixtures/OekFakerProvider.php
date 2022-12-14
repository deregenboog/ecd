<?php

namespace OekBundle\DataFixtures;

use AppBundle\Entity\Medewerker;
use Faker\Provider\DateTime;
use OekBundle\Entity\Aanmelding;
use OekBundle\Entity\Afsluiting;
use OekBundle\Entity\VerwijzingDoor;
use OekBundle\Entity\VerwijzingNaar;

final class OekFakerProvider
{
    public function aanmelding(VerwijzingDoor $verwijzing, Medewerker $medewerker)
    {
        $aanmelding = new Aanmelding();
        $aanmelding
            ->setDatum(DateTime::dateTimeBetween('-5 years', '2021-12-31'))
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker);

        return $aanmelding;
    }

    public function afsluiting(VerwijzingNaar $verwijzing, Medewerker $medewerker)
    {
        $afsluiting = new Afsluiting();
        $afsluiting
            ->setDatum(DateTime::dateTimeBetween('2022-01-01'))
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker);

        return $afsluiting;
    }
}
