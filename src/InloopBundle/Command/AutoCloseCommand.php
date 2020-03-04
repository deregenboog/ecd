<?php

namespace InloopBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use InloopBundle\Entity\Afsluiting;
use InloopBundle\Entity\RedenAfsluiting;
use InloopBundle\Entity\Registratie;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AutoCloseCommand extends ContainerAwareCommand
{
    private $years = 3;

    private $toelichting = 'Automatisch door systeem afgesloten.';

    private $redenPattern = '%geen inloophuis bezocht%';

    protected function configure()
    {
        $this
            ->setName('inloop:automatisch-afsluiten')
            ->setHelp(sprintf('Sluit inloopdossiers automatisch af als er %d jaar geen inloophuis bezocht is.', $this->years))
            ->addArgument('batch-size', InputArgument::OPTIONAL, 'Batch size', 100)
            ->addOption('dry-run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        try {
            $reden = $entityManager->getRepository(RedenAfsluiting::class)->createQueryBuilder('reden')
                ->where('reden.naam LIKE :reden')
                ->setParameter('reden', $this->redenPattern)
                ->getQuery()
                ->getSingleResult()
            ;
        } catch (NonUniqueResultException $e) {
            $output->writeln("Reden '{$this->redenPattern}' is niet uniek!");

            return;
        } catch (NoResultException $e) {
            $output->writeln("Reden '{$this->redenPattern}' niet gevonden!");

            return;
        }

        $klanten = $entityManager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->innerJoin(Registratie::class, 'registratie', 'WITH', 'registratie.klant = klant')
            ->leftJoin('klant.huidigeStatus', 'status')
            ->where('status NOT INSTANCE OF '.Afsluiting::class)
            ->groupBy('klant.id')
            ->having('MAX(registratie.binnen) < :long_time_ago')
            ->setParameter('long_time_ago', new \DateTime(sprintf('-%d years', $this->years)))
            ->setMaxResults($input->getArgument('batch-size'))
            ->getQuery()
            ->getResult();

        $output->writeln(sprintf(
            '%d klanten gevonden om af te sluiten met reden "%s" en toelichting "%s"',
            count($klanten),
            $reden->getNaam(),
            $this->toelichting
        ));
        foreach ($klanten as $klant) {
            $output->writeln(sprintf(' - klant %d afsluiten', $klant->getId()));

            if (!$input->getOption('dry-run')) {
                $afsluiting = new Afsluiting($klant);
                $afsluiting->setReden($reden)->setToelichting($this->toelichting);
                $entityManager->persist($afsluiting);
            }
        }

        if (!$input->getOption('dry-run')) {
            $entityManager->flush();
        }
    }
}
