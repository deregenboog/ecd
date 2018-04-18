<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Medewerker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new Medewerker();
        $admin
            ->setUsername('admin')
            ->setPlainPassword('admin-password')
            ->setEmail('admin@example.com')
            ->setEnabled(true)
            ->addRole('ROLE_ADMIN')
        ;
        $manager->persist($admin);

        $manager->flush();
    }
}
