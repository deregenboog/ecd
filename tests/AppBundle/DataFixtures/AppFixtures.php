<?php

namespace Tests\AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Entity\Land;
use AppBundle\Entity\Nationaliteit;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadGeslachten($manager);
        $this->loadLanden($manager);
        $this->loadNationaliteiten($manager);

        $medewerker = new Medewerker();
        $medewerker->setUsername('user_1');
        $manager->persist($medewerker);
        $this->setReference('medewerker_1', $medewerker);

        $klant = new Klant();
        $klant
            ->setMedewerker($medewerker)
            ->setLand($this->getReference('land_nederland'))
            ->setNationaliteit($this->getReference('nationaliteit_nederlandse'))
        ;
        $manager->persist($klant);
        $this->setReference('klant_1', $klant);

        $vrijwilliger = new Vrijwilliger();
        $vrijwilliger
            ->setMedewerker($medewerker)
            ->setLand($this->getReference('land_nederland'))
            ->setNationaliteit($this->getReference('nationaliteit_nederlandse'))
        ;
        $manager->persist($vrijwilliger);
        $this->setReference('vrijwilliger_1', $vrijwilliger);

        $manager->flush();
    }

    public function loadGeslachten(ObjectManager $manager)
    {
        $geslacht = new Geslacht('Man', 'M');
        $manager->persist($geslacht);
        $this->setReference('geslacht_man', $geslacht);

        $geslacht = new Geslacht('Vrouw', 'V');
        $manager->persist($geslacht);
        $this->setReference('geslacht_vrouw', $geslacht);
    }

    public function loadLanden(ObjectManager $manager)
    {
        $land = new Land('Nederland', 'NL', 'NLD');
        $manager->persist($land);
        $this->setReference('land_nederland', $land);
    }

    public function loadNationaliteiten(ObjectManager $manager)
    {
        $nationaliteit = new Nationaliteit('Nederlandse', 'NL');
        $manager->persist($nationaliteit);
        $this->setReference('nationaliteit_nederlandse', $nationaliteit);
    }
}
