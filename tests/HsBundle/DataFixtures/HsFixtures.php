<?php

namespace Tests\HsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use HsBundle\Entity\Dienstverlener;
use AppBundle\Entity\Geslacht;
use HsBundle\Entity\Activiteit;
use Tests\AppBundle\DataFixtures\AppFixtures;

class HsFixtures extends AppFixtures
{
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $activiteit = new Activiteit('Schilderen');
        $manager->persist($activiteit);

        $klant = new Klant();
        $klant
            ->setGeslacht($geslacht)
            ->setMedewerker($medewerker)
        ;
        $manager->persist($klant);

//         $dienstverlener = new Dienstverlener();
//         $manager->persist($dienstverlener);

        $klus = new Klus($klant, $medewerker);
        $klus->setActiviteit($activiteit);
        $manager->persist($klus);

        $manager->flush();
    }
}
