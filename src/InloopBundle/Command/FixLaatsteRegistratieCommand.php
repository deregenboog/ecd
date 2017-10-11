<?php

namespace InloopBundle\Command;

use AppBundle\Entity\Klant;
use InloopBundle\Entity\Registratie;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixLaatsteRegistratieCommand extends ContainerAwareCommand
{
    private $interval = '-2 months';

    protected function configure()
    {
        $this->setName('inloop:access:deny');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $manager \Doctrine\ORM\EntityManager */
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // get clients that haven't visited within the configured interval
        $klanten = $manager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->select('klant, intake')
            ->innerJoin('klant.laatsteIntake', 'intake', 'WITH', 'intake.toegangInloophuis = 1')
            ->innerJoin(Registratie::class, 'registratie', 'WITH', 'registratie.klant = klant')
            ->innerJoin('registratie.locatie', 'locatie', 'WITH', 'locatie.gebruikersruimte = 1')
            ->groupBy('klant.id')
            ->having('MAX(registratie.binnen) < :date')
            ->setParameter('date', new \DateTime($this->interval))
            ->getQuery()
            ->getResult()
        ;

        $output->writeln(sprintf('%d klanten gevonden', count($klanten)));

        foreach ($klanten as $klant) {
            // deny access
            $output->writeln(sprintf('Toegang ontzeggen voor klant #%d', $klant->getId()));
            $klant->getLaatsteIntake()->setToegangInloophuis(false);
            break;
        }

        $manager->flush();

        $output->writeln('Succesvol afgerond');
    }
}
