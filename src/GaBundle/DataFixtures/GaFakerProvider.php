<?php

namespace GaBundle\DataFixtures;

use AppBundle\Entity\Medewerker;
use Faker\Provider\DateTime;
use GaBundle\Entity\Aanmelding;
use GaBundle\Entity\Afsluiting;
use GaBundle\Entity\Dossier;
use GaBundle\Entity\VerwijzingDoor;
use GaBundle\Entity\VerwijzingNaar;

final class GaFakerProvider
{
    public function gaAanmelding(VerwijzingDoor $verwijzing, Medewerker $medewerker)
    {
        $aanmelding = new Aanmelding();
        $aanmelding
            ->setDatum(DateTime::dateTimeBetween('-5 years', '2017-01-01'))
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker)
        ;

        return $aanmelding;
    }

    public function gaAfsluiting(VerwijzingNaar $verwijzing, Medewerker $medewerker, Dossier $dossier)
    {
        $afsluiting = new Afsluiting();
        $afsluiting
            ->setDatum(DateTime::dateTimeBetween('2017-01-01'))
            ->setVerwijzing($verwijzing)
            ->setMedewerker($medewerker)
        ;

        return $afsluiting;
    }
}
