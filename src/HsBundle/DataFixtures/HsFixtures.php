<?php

namespace HsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Activiteit;
use HsBundle\Entity\Vrijwilliger;

class HsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $geslacht = $this->getReference('geslacht_man');
        $medewerker = $this->getReference('medewerker_1');
        $appKlant = $this->getReference('klant_1');
        $appVrijwilliger = $this->getReference('vrijwilliger_1');

        $activiteit = new Activiteit('Schilderen');
        $manager->persist($activiteit);

        $klant = new Klant();
        $klant->setGeslacht($geslacht)->setMedewerker($medewerker);
        $manager->persist($klant);

        $dienstverlener = new Dienstverlener($appKlant);
        $manager->persist($dienstverlener);

        $vrijwilliger = new Vrijwilliger($appVrijwilliger);
        $manager->persist($vrijwilliger);

        $klus = new Klus($klant, $medewerker);
        $klus
            ->setActiviteit($activiteit)
            ->addDienstverlener($dienstverlener)
            ->addVrijwilliger($vrijwilliger)
        ;
        $manager->persist($klus);

        $manager->flush();
    }
}
