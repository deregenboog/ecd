<?php

namespace MwBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;

use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class FixDossierstatusCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManager
     */
    private $manager;



    protected function configure()
    {
        $this->setName('mw:dossierstatus:fix');
        $this->addUsage("Limited to 1000 per run. Please use fromId option to start from another klantId. Do several runs for the whole batch. (memory)");
        $this->addOption('dry-run');
        $this->addOption('fromId', null,InputArgument::OPTIONAL, 'Start from klantId');
        $this->addOption('id', null,InputArgument::OPTIONAL, 'Only for this klantId');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // get clients that haven't visited within the configured interval
//        $klanten = $this->getKlanten();

        $fromId = $input->getOption('fromId');
        $id = $input->getOption('id');


        $mwKlanten = $this->getMwKlanten($fromId,$id);



        $output->writeln(sprintf('%d MwKlanten gevonden from id: %d', is_array($mwKlanten) || $mwKlanten instanceof \Countable ? count($mwKlanten) : 0,$fromId));



        foreach ($mwKlanten as $klant) {

            $output->writeln(sprintf("Bezig met klant %s",$klant->getId() ) );
            $vorigeMwStatus = new \stdClass();

            foreach($klant->getMwStatussen() as $mwStatus)
            {

                if($mwStatus instanceof $vorigeMwStatus)
                {
                    $output->writeln(sprintf("DossierStatus aan het verwijderen (id: %s)",$vorigeMwStatus->getId()) );
                    $this->manager->remove($vorigeMwStatus);
                }
                $vorigeMwStatus = $mwStatus;
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
            ->select('klant')
            ->where("klant.huidigeMwStatus IS NOT NULL")
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
