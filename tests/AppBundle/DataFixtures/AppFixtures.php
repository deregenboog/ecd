<?php

namespace Tests\AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Medewerker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $medewerker = new Medewerker();
        $manager->persist($medewerker);

        $geslacht = new Geslacht('Man', 'M');
        $manager->persist($geslacht);

        $manager->flush();
    }
}
