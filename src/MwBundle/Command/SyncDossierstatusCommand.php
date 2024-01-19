<?php

namespace MwBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManagerInterface;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class SyncDossierstatusCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;


    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }
    /**
     * Creates mwDossierStatus for MW klanten die gesynchroniseerd is aan het inloopdossier, als ze die hebben.
     * Zo niet dan maakt ie sowieso een nieuwe aan.
     */
    protected function configure()
    {
        $this->setName('mw:dossierstatus:sync');
        $this->addUsage("Limited to 1000 per run. Please use fromId option to start from another klantId. Do several runs for the whole batch. (memory)");
        $this->addOption('dry-run');
        $this->addOption('fromId', null,InputArgument::OPTIONAL, 'Start from klantId');
        $this->addOption('id', null,InputArgument::OPTIONAL, 'Only for this klantId');
        $this->addOption('onlyWithoutInloopDossier');
        $this->addOption('vanafVerslagDatum', null,InputArgument::OPTIONAL, 'Alleen met verslagen vanaf deze datum');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        // get clients that haven't visited within the configured interval
//        $klanten = $this->getKlanten();

        $fromId = $input->getOption('fromId');
        $id = $input->getOption('id');
        $onlyWithoutInloop = $input->getOption('onlyWithoutInloopDossier');
        $vanafVerslagDatum = $input->getOption('vanafVerslagDatum');
        if($onlyWithoutInloop)
        {
            $output->writeln(sprintf('Only without inloopdossier'));
            $mwKlanten = $this->getMwKlantenZonderDossierStatus($fromId,$id,$output);
        }
        else
        {
            $mwKlanten = $this->getMwKlanten($fromId,$id,$vanafVerslagDatum);
        }


        $output->writeln(sprintf('%d MwKlanten gevonden from id: %d', is_array($mwKlanten) || $mwKlanten instanceof \Countable ? count($mwKlanten) : 0,$fromId));


        foreach ($mwKlanten as $klant) {

            $huidigeStatus = $klant->getHuidigeStatus();

            if($huidigeStatus && $huidigeStatus->isAangemeld()) {
                $status = new Aanmelding();
                $status->setKlant($klant);
                $output->writeln(sprintf('Aanmelding toevoegen voor #%d', $klant->getId()));
                $klant->setHuidigeMwStatus($status);
            }
            elseif($huidigeStatus && $huidigeStatus->isAfgesloten())
            {
                $status = new Afsluiting();
                $status->setKlant($klant);
                $output->writeln(sprintf('Afsluiting toevoegen voor #%d', $klant->getId()));
                $klant->setHuidigeMwStatus($status);
            }
            else //klant heeft geen inloopdossier.
            {
                $status = new Aanmelding();
                $status->setKlant();
                $output->writeln(sprintf('Aanmelding toevoegen voor #%d (geen InloopDossier)', $klant->getId()));
                $klant->setHuidigeMwStatus($status);
            }
        }
        if(!$input->getOption('dry-run')) {
            $this->manager->flush();
        }
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

    private function getMwKlanten($fromId=null,$id=null,$fromDate=null)
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
        if($id !== null)
        {
            $builder->andWhere('klant.id = :id');
            $builder->setParameter('id',$id);

        }
        if($fromDate !== null)
        {
            $builder->andWhere('verslag.datum >= :fromDate')
                ->andWhere("klant.huidigeMwStatus IS NULL")
                ->setParameter("fromDate",$fromDate);
        }
        $builder->setMaxResults(5000);

        return $builder
            ->getQuery()
            ->getResult()
            ;
    }

    private function getMwKlantenZonderDossierStatus($fromId=null,$id=null,$output=null)
    {
        $builder = $this->manager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->select('klant')
            ->leftJoin('klant.huidigeStatus', 'status')
            ->leftJoin('klant.huidigeMwStatus','mwStatus')
            ->innerJoin('klant.verslagen', 'verslag')

            ->andWhere('verslag.id IS NOT NULL')
            ->andWhere('mwStatus.id IS NULL')

            ->groupBy('klant.id');
        if($fromId !== null)
        {
            $builder->andWhere('klant.id >= :fromId');
            $builder->setParameter('fromId',$fromId);
        }
            if($id !== null)
        {
            $builder->andWhere('klant.id = :id');
            $builder->setParameter('id',$id);

        }
        $builder->setMaxResults(5000);

//        $output->writeln($builder->getQuery()->getSQL());
        return $builder
            ->getQuery()
            ->getResult()
            ;
    }
}
