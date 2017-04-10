<?php

namespace OekBundle\DataFixtures;

use OekBundle\Entity\OekAanmelding;
use OekBundle\Entity\OekVerwijzingDoor;
use Faker\Provider\DateTime;
use OekBundle\Entity\OekVerwijzingNaar;
use OekBundle\Entity\OekAfsluiting;
use AppBundle\Entity\Medewerker;

final class OekFakerProvider
{
    public function oekAanmelding(OekVerwijzingDoor $verwijzing, Medewerker $medewerker)
    {
        $aanmelding = new OekAanmelding();
        $aanmelding
            ->setDatum(DateTime::dateTimeBetween('-5 years', '2017-01-01'))
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker);

        return $aanmelding;
    }

    public function oekAfsluiting(OekVerwijzingNaar $verwijzing, Medewerker $medewerker)
    {
        $afsluiting = new OekAfsluiting();
        $afsluiting
            ->setDatum(DateTime::dateTimeBetween('2017-01-01'))
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker);

        return $afsluiting;
    }
}
