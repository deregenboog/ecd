<?php

namespace MwBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;

use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncDossierstatusCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $manager;



    protected function configure()
    {
        $this->setName('mw:dossierstatus:sync');
       $this->addOption('dry-run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // get clients that haven't visited within the configured interval
//        $klanten = $this->getKlanten();

        $mwKlanten = $this->getMwKlanten();

        $output->writeln(sprintf('%d MwKlanten gevonden', count($mwKlanten)));


        foreach ($mwKlanten as $klant) {

            $huidigeStatus = $klant->getHuidigeStatus();
            if($huidigeStatus->isAangemeld()) {
                $aanmelding = new Aanmelding($klant);
                $output->writeln(sprintf('Aanmelding toevoegen voor #%d', $klant->getId()));
            }
            elseif($huidigeStatus->isAfgesloten())
            {
                $afsluiting = new Afsluiting($klant);
                $output->writeln(sprintf('Afsluiting toevoegen voor #%d', $klant->getId()));
            }
            $klant->setHuidigeMwStatus($afsluiting);
        }

        if(!$input->getOption('dry-run')) {
            $this->manager->flush();
        }


        $output->writeln('Succesvol afgerond');
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

    private function getMwKlanten()
    {
        return $this->manager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->select('klant')
            ->leftJoin('klant.huidigeStatus', 'status')
            ->leftJoin('klant.verslagen', 'verslag')

            ->andWhere('verslag.id IS NOT NULL')

            ->groupBy('klant.id')
            ->getQuery()
            ->getResult()
        ;
    }
}
