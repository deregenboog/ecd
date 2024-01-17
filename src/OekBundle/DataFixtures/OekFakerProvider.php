<?php

namespace OekBundle\DataFixtures;

use AppBundle\Entity\Medewerker;
use Faker\Provider\Base;
use Faker\Provider\DateTime;
use OekBundle\Entity\Aanmelding;
use OekBundle\Entity\Afsluiting;
use OekBundle\Entity\VerwijzingDoor;
use OekBundle\Entity\VerwijzingNaar;

final class OekFakerProvider extends Base
{
    public function aanmelding(VerwijzingDoor $verwijzing, Medewerker $medewerker)
    {
        $aanmelding = new Aanmelding();
        $aanmelding
            ->setDatum(DateTime::dateTimeBetween('-5 years', new \DateTime("first day of this year")))
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker);

        return $aanmelding;
    }

    public function afsluiting(VerwijzingNaar $verwijzing, Medewerker $medewerker)
    {
        $afsluiting = new Afsluiting();
        $afsluiting
            ->setDatum(DateTime::dateTimeBetween(new \DateTime("first day of this year")) )
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker);

        return $afsluiting;
    }
}
