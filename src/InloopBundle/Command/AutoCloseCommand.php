<?php

namespace InloopBundle\Command;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use InloopBundle\Entity\Afsluiting;
use InloopBundle\Entity\RedenAfsluiting;
use InloopBundle\Entity\Registratie;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AutoCloseCommand extends \Symfony\Component\Console\Command\Command
{
    private $years = 2;

    private $toelichting = 'Automatisch door systeem afgesloten.';

    private $redenPattern = '%geen inloophuis bezocht%';

    private $defaultUsername = 'jvloo';

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('inloop:automatisch-afsluiten')
            ->setHelp(sprintf('Sluit inloopdossiers automatisch af als er %d jaar geen inloophuis bezocht is.', $this->years))
            ->addArgument('batch-size', InputArgument::OPTIONAL, 'Batch size', 500)
            ->addOption('dry-run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $reden = $this->entityManager->getRepository(RedenAfsluiting::class)->createQueryBuilder('reden')
                ->where('reden.naam LIKE :reden')
                ->setParameter('reden', $this->redenPattern)
                ->getQuery()
                ->getSingleResult()
            ;
        } catch (NonUniqueResultException $e) {
            $output->writeln("Reden '{$this->redenPattern}' is niet uniek!");

            return 0;
        } catch (NoResultException $e) {
            $output->writeln("Reden '{$this->redenPattern}' niet gevonden!");

            return 0;
        }

        try {
            $defaultMedewerker = $this->entityManager->getRepository(Medewerker::class)
                ->createQueryBuilder('medewerker')
                ->where('medewerker.username = :defaultUsername')
                ->setParameter('defaultUsername', $this->defaultUsername)
                ->getQuery()
                ->getSingleResult()
            ;
        } catch (NonUniqueResultException $e) {
            $output->writeln("Default username '{$this->defaultUsername}' is niet uniek! Teveel resultaten...");

            return 0;
        } catch (NoResultException $e) {
            $output->writeln("Username '{$this->defaultUsername}' niet gevonden!");

            return 0;
        }

        $builder = $this->entityManager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->select('klant, registratie.binnen AS laatsteRegistratieDatum')
            ->leftJoin(Registratie::class, 'registratie', 'WITH', 'registratie.klant = klant')
            ->leftJoin('klant.huidigeStatus', 'status')
            ->where('status NOT INSTANCE OF '.Afsluiting::class)
            ->groupBy('klant.id')
            ->having('MAX(registratie.binnen) < :long_time_ago')
            ->setParameter('long_time_ago', new \DateTime(sprintf('-%d years', $this->years)))
            ->setMaxResults($input->getArgument('batch-size'));


        $klanten = $builder
            ->getQuery()
            ->getResult();

        $output->writeln(sprintf(
            '%d klanten gevonden om af te sluiten met reden "%s" en toelichting "%s"',
            is_array($klanten) || $klanten instanceof \Countable ? count($klanten) : 0,
            $reden->getNaam(),
            $this->toelichting
        ));


        foreach ($klanten as $klant) {
            $laatsteRegistratieDatum = $klant['laatsteRegistratieDatum'];
//            dump($laatsteRegistratieDatum);
            $laatsteRegistratieDatum = $laatsteRegistratieDatum->modify('+'.$this->years.' years');
//            dump($laatsteRegistratieDatum);
            $klant = $klant[0];
            $output->writeln(sprintf(' - klant %d afsluiten', $klant->getId()));

            if (!$input->getOption('dry-run')) {

                $afsluiting = new Afsluiting($defaultMedewerker);
                $afsluiting->setKlant($klant);
                $afsluiting->setDatum($laatsteRegistratieDatum);
                $afsluiting->setReden($reden)->setToelichting($this->toelichting);

                $this->entityManager->persist($afsluiting);
                $klant->setHuidigeStatus($afsluiting);
                $this->entityManager->persist($klant);

            }
        }

        // now find klanten who did never register but have an aanmelding.
        $builder = $this->entityManager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->select('klant', 'laatsteIntake.intakedatum AS laatsteIntakeDatum')
            ->leftJoin(Registratie::class, 'registratie', 'WITH', 'registratie.klant = klant')
            ->leftJoin('klant.huidigeStatus', 'status')
            ->innerJoin('klant.laatsteIntake','laatsteIntake')
            ->where('status NOT INSTANCE OF '.Afsluiting::class)
            ->andWhere('registratie.id IS NULL')
            ->andWhere('laatsteIntake.intakedatum < :long_time_ago')
            ->groupBy('klant.id')
            ->setParameter('long_time_ago', new \DateTime(sprintf('-%d years', $this->years)))
            ->setMaxResults($input->getArgument('batch-size'));


        $klanten = $builder
            ->getQuery()
            ->getResult();

        $output->writeln(sprintf(
            '%d klanten gevonden om af te sluiten met die na intake nooit een inloophuis hebben bezocht. Reden: %s, toelichting %s"',
            is_array($klanten) || $klanten instanceof \Countable ? count($klanten) : 0,
            $reden->getNaam(),
            $this->toelichting
        ));


        foreach ($klanten as $klant) {
            $laatsteIntakeDatum = $klant['laatsteIntakeDatum'];
            $laatsteIntakeDatum = $laatsteIntakeDatum->modify('+'.$this->years.' years');
            $klant = $klant[0];
            $output->writeln(sprintf(' - klant %d afsluiten', $klant->getId()));

            if (!$input->getOption('dry-run')) {

                $afsluiting = new Afsluiting($defaultMedewerker);
                $afsluiting->setKlant($klant);
                $afsluiting->setDatum($laatsteIntakeDatum);
                $afsluiting->setReden($reden)->setToelichting($this->toelichting);

                $this->entityManager->persist($afsluiting);
                $klant->setHuidigeStatus($afsluiting);
                $this->entityManager->persist($klant);

            }
        }

        if (!$input->getOption('dry-run')) {
            $this->entityManager->flush();
        }

        return 0;
    }
}
