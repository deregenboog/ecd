<?php

namespace VillaBundle\Command;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use VillaBundle\Entity\Aanmelding;
use VillaBundle\Entity\Afsluiting;
use VillaBundle\Entity\AfsluitredenSlaper;
use VillaBundle\Entity\DossierStatus;
use VillaBundle\Entity\Slaper;
use VillaBundle\Service\SlaperDao;
use VillaBundle\Service\SlaperDaoInterface;

class AutoCloseDossiersCommand extends \Symfony\Component\Console\Command\Command
{
    private $months = 12;

    private $toelichting = 'Automatisch door systeem afgesloten.';

    private $redenPattern = 'Automatisch%';

    private $defaultUsername = 'edrooij';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SlaperDao  */
    private $slaperDao;

    public function __construct(EntityManagerInterface $entityManager, SlaperDaoInterface $slaperDao)
    {
        $this->entityManager = $entityManager;
        $this->slaperDao = $slaperDao;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('villa:automatisch-afsluiten')
            ->setHelp(sprintf('Sluit Villa dossiers automatisch af %d maanden na startdatum.', $this->months))
            ->addArgument('batch-size', InputArgument::OPTIONAL, 'Batch size', 500)
            ->addOption('dry-run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $reden = $this->entityManager->getRepository(AfsluitredenSlaper::class)->createQueryBuilder('reden')
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



        $builder = $this->entityManager->getRepository(Slaper::class)->createQueryBuilder('slaper')
            ->select("slaper, appKlant")
            ->innerJoin('slaper.appKlant', 'appKlant')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')


        ;
        $this->slaperDao->addDossierStatusToQueryBuilder($builder, 'slaper', 'dossierStatussen', 'slaper', Aanmelding::class);

        $builder->andWhere("ds.datum < :twelveMonthsAgo");
        $builder->setParameter("twelveMonthsAgo", (new \DateTime())->modify("-12 months") );

        $slapers = $builder
            ->getQuery()
            ->getResult();


        $output->writeln(sprintf(
            '%d slapers gevonden om af te sluiten met reden "%s" en toelichting "%s"',
            is_array($slapers) || $slapers instanceof \Countable ? count($slapers) : 0,
            $reden->getNaam(),
            $this->toelichting
        ));


        foreach ($slapers as $slaper) {
            $now = new \DateTime();

            $output->writeln(sprintf(' - slaper %d afsluiten', $slaper->getId()));

            if (!$input->getOption('dry-run')) {

                $afsluiting = new Afsluiting();
                $afsluiting->setMedewerker($defaultMedewerker);
                $afsluiting->setSlaper($slaper);
                $afsluiting->setDatum($now);
                $afsluiting->setReden($reden);

                $this->entityManager->persist($afsluiting);
                $slaper->addDossierStatus($afsluiting);
                $this->entityManager->persist($slaper);

            }
        }


        if (!$input->getOption('dry-run')) {
            $this->entityManager->flush();
        }

        return 0;
    }
}
