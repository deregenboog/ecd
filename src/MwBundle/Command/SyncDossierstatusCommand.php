<?php

namespace MwBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;

use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class SyncDossierstatusCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $manager;



    protected function configure()
    {
        $this->setName('mw:dossierstatus:sync');
        $this->addUsage("Limited to 1000 per run. Please use fromId option to start from another klantId. Do several runs for the whole batch. (memory)");
        $this->addOption('dry-run');
        $this->addOption('fromId', null,InputArgument::OPTIONAL, 'Start from klantId');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // get clients that haven't visited within the configured interval
//        $klanten = $this->getKlanten();

        $fromId = $input->getOption('fromId');
        $mwKlanten = $this->getMwKlanten($fromId);

        $output->writeln(sprintf('%d MwKlanten gevonden from id: %d', count($mwKlanten),$fromId));


        foreach ($mwKlanten as $klant) {

            $huidigeStatus = $klant->getHuidigeStatus();

            if($huidigeStatus && $huidigeStatus->isAangemeld()) {
                $status = new Aanmelding($klant);
                $output->writeln(sprintf('Aanmelding toevoegen voor #%d', $klant->getId()));
                $klant->setHuidigeMwStatus($status);
            }
            elseif($huidigeStatus && $huidigeStatus->isAfgesloten())
            {
                $status = new Afsluiting($klant);
                $output->writeln(sprintf('Afsluiting toevoegen voor #%d', $klant->getId()));
                $klant->setHuidigeMwStatus($status);
            }
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

    private function getMwKlanten($fromId=null)
    {
        $builder = $this->manager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->select('klant')
            ->leftJoin('klant.huidigeStatus', 'status')
            ->leftJoin('klant.verslagen', 'verslag')

            ->andWhere('verslag.id IS NOT NULL')

            ->groupBy('klant.id');
        if($fromId !== null)
        {
            $builder->andWhere('klant.id >= :fromId');
            $builder->setParameter('fromId',$fromId);
        }
        $builder->setMaxResults(1000);

        return $builder
            ->getQuery()
            ->getResult()
        ;
    }
}
