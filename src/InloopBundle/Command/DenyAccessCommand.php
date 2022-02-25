<?php

namespace InloopBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Registratie;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DenyAccessCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManager
     */
    private $manager;

    private $interval = '-2 months';

    protected function configure()
    {
        $this->setName('inloop:access:deny');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // get clients that haven't visited within the configured interval
        $klanten = $this->getKlanten();
        $output->writeln(sprintf('%d klanten gevonden', is_array($klanten) || $klanten instanceof \Countable ? count($klanten) : 0));

        foreach ($klanten as $klant) {
            // deny access
            $output->writeln(sprintf('Toegang ontzeggen voor klant #%d', $klant->getId()));
//             $klant->getLaatsteIntake()->setToegangInloophuis(false);
//             break;
        }

//         $this->manager->flush();

        $output->writeln('Succesvol afgerond');
        return 0;
    }

    private function getKlanten()
    {
        return $this->manager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->select('klant, intake')
            ->innerJoin('klant.laatsteIntake', 'intake', 'WITH', 'intake.toegangInloophuis = 1 AND intake.magGebruiken = 1')
            ->innerJoin(Registratie::class, 'registratie', 'WITH', 'registratie.klant = klant')
            ->innerJoin('registratie.locatie', 'locatie', 'WITH', 'locatie.gebruikersruimte = 1')
            ->groupBy('klant.id')
            ->having('MAX(registratie.binnen) < :date')
            ->setParameter('date', new \DateTime($this->interval))
            ->getQuery()
            ->getResult()
        ;
    }
}
