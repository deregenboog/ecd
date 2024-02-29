<?php

namespace DagbestedingBundle\Command;

use AppBundle\Entity\Medewerker;
use AppBundle\Exception\UserException;
use DagbestedingBundle\DagbestedingBundle;
use DagbestedingBundle\Entity\Beschikbaarheid;
use DagbestedingBundle\Entity\Deelname;
use DagbestedingBundle\Entity\Document;
use DagbestedingBundle\Entity\Locatie;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Entity\Resultaatgebied;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Entity\Trajectcoach;
use DagbestedingBundle\Entity\Trajectsoort;
use DagbestedingBundle\Entity\Verslag;
use DagbestedingBundle\Entity\Werkdoel;
use DagbestedingBundle\Service\LocatieDao;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ScipBundle\Entity\Deelnemer;
use ScipBundle\Service\DeelnemerDao;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class MigrateCommand extends Command
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var DeelnemerDao */
    private $scipDeelnemerDao;

    /** @var \DagbestedingBundle\Service\DeelnemerDao */
    private $dbDeelnemerDao;

    /** @var \DagbestedingBundle\Service\ProjectDao */
    private $dbProjectenDao;

    /** @var \DagbestedingBundle\Service\TrajectcoachDao */
    private $trajectcoachDao;

    /** @var LocatieDao */
    private $locatieDao;

    private $dbProjects = [];

    /** @var Resultaatgebiedsoort */
    private $defaultResultaatgebiedSoort;

    /** @var  Trajectsoort */
    private $defaultTrajectsoort;

    /** @var  Trajectsoort */
    private $wmoTrajectsoort;

    /** @var Medewerker */
    private $defaultMedewerker;

    /** @var Trajectcoach */
    private $defaultTrajectcoach;

    /** @var EntityRepository */
    private $trajectCoachRep;

    /** @var EntityRepository */
    private $locatieRep;

    private $projectLocations = [];

    private $projectTrajectcoaches=[];

    /** @var InputInterface */
    private $input;

    /** @var OutputInterface */
    private $output;

    public function __construct(
        EntityManagerInterface $em,
        DeelnemerDao $scipDeelnemerDao,
        \DagbestedingBundle\Service\DeelnemerDao $dbDeelnemerDao,
        \DagbestedingBundle\Service\ProjectDao $dbProjectenDao,
        \DagbestedingBundle\Service\TrajectcoachDao $trajectcoachDao,
        LocatieDao $locatieDao
    ) {
        $this->em = $em;
        $this->scipDeelnemerDao = $scipDeelnemerDao;
        $this->dbDeelnemerDao = $dbDeelnemerDao;
        $this->dbProjectenDao = $dbProjectenDao;
        $this->trajectcoachDao = $trajectcoachDao;
        $this->locatieDao = $locatieDao;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('dagbesteding:deelnemers:migratescip')
            ->addOption('dry-run');

    }

    private function init()
    {
        $this->projectLocations["ACC Noord"] = "Scip Distelweg";
        $this->projectLocations["ACC West"] = "Scip Cornelis Dirkszstraat";
        $this->projectLocations["Balie CD"] = "Scip Cornelis Dirkszstraat";
        $this->projectLocations["Balie KG"] = "Scip Keizersgracht";
        $this->projectLocations["Digivibes"] = "Scip Cornelis Dirkszstraat";
        $this->projectLocations["Ervaringswijzer"] = "Scip Keizersgracht";
        $this->projectLocations["Login Werkplaats"] = "Scip Cornelis Dirkszstraat";
        $this->projectLocations["Login Winkel Noord"] = "Scip Distelweg";
        $this->projectLocations["Login Winkel West"] = "Scip Kwakersstraat";
        $this->projectLocations["Tobi Vroegh"] = "Scip Cornelis Dirkszstraat";
        $this->projectLocations["Webbureau"] = "Scip Keizersgracht";

        $this->projectTrajectcoaches["ACC Noord"] = "Rolanda van Embricqs";
        $this->projectTrajectcoaches["ACC West"] = "Kabul Veldhoen";
        $this->projectTrajectcoaches["Balie CD"] = "Sapho Post";
        $this->projectTrajectcoaches["Balie KG"] = "Mirjam Snitjer";
        $this->projectTrajectcoaches["Digivibes"] = "Arno Kooij";
        $this->projectTrajectcoaches["Ervaringswijzer"] = "Mirjam Snitjer";
        $this->projectTrajectcoaches["Login Werkplaats"] = "Kabul Veldhoen";
        $this->projectTrajectcoaches["Login Winkel Noord"] = "Rolanda van Embricqs";
        $this->projectTrajectcoaches["Login Winkel West"] = "Kabul Veldhoen";
        $this->projectTrajectcoaches["Tobi Vroegh"] = "Sapho Post";
        $this->projectTrajectcoaches["Webbureau"] = "Mirjam Snitjer";

        $this->locatieRep = $this->em->getRepository(Locatie::class);
        $this->trajectCoachRep = $this->em->getRepository(Trajectcoach::class);

        $this->defaultResultaatgebiedSoort = $this->em->getRepository(Resultaatgebiedsoort::class)
            ->findOneBy(["naam"=>"NVT"]);

        $this->defaultTrajectsoort = $this->em->getRepository(Trajectsoort::class)
            ->findOneBy(["naam"=>"SCIP"]);

        $this->wmoTrajectsoort = $this->em->getRepository(Trajectsoort::class)
            ->findOneBy(["naam"=>"WMO"]);

        $this->defaultTrajectcoach = $this->trajectCoachRep
            ->findOneBy(["id"=>"20837"]); //20837

        $this->defaultMedewerker = $this->em->getRepository(Medewerker::class)
            ->findOneBy(["username"=>"slovdahl"]); //slovdahl

        $this->mapAndCreateProjects();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->input = $input;
        $this->output = $output;

        $this->init();


        $scipDeelnemers = $this->scipDeelnemerDao->findAll();

        $this->output->writeln("Retrieving SCIP deelnemers: ".count($scipDeelnemers));

//       $this->output->write(array_keys($this->dbProjects));


        $i=1;

        /**
         * @var Deelnemer $scipDeelnemer
         */
        foreach($scipDeelnemers as $scipDeelnemer)
        {
           $this->output->writeln($i." -----");

//            if(!$scipDeelnemer->isActief()){
//               $this->output->writeln("Skip inactive deelnemer: ".$scipDeelnemer->getKlant()->getNaam());
//                continue;
//            }

//            if(null == $scipDeelnemer->getProjecten()){
//               $this->output->writeln("Skip deelnemer without projecten: ".$scipDeelnemer->getKlant()->getNaam());
//                continue;
//            }

            /**
             * @var \DagbestedingBundle\Entity\Deelnemer $dbDeelnemer;
             */
            $dbDeelnemer = $this->dbDeelnemerDao->findOneByKlant($scipDeelnemer->getKlant());

            try {
                if(null == $dbDeelnemer ) {
//               $this->output->write(dump($scipDeelnemer->getKlant()));
                    $this->output->writeln("Create new deelnemer: ".$scipDeelnemer->getKlant()->getNaam());
                    $dbDeelnemer = $this->addDeelnemer($scipDeelnemer, $output);
                    $this->output->writeln("Persist new deelnemer: ".$dbDeelnemer->getKlant()->getNaam());
//               $this->output->writeln(($this->input->getOption('dry-run') == false));
                    (!$this->input->getOption('dry-run'))?  $this->dbDeelnemerDao->create($dbDeelnemer):null;
                } else {
                    $this->output->writeln("Migrate existing deelnemer: ".$scipDeelnemer->getKlant()->getNaam());
                    $this->migrateDeelnemer($scipDeelnemer, $dbDeelnemer, $output);

                    $this->output->writeln("Persist existing deelnemer: ".$dbDeelnemer->getKlant()->getNaam());
                    (!$this->input->getOption('dry-run'))? $this->dbDeelnemerDao->update($dbDeelnemer):null;
                }
            } catch (UserException $e)
            {
                $this->output->writeln("Exception when trying to migrate deelnemer: ".$scipDeelnemer->getKlant()->getNaam()." . Message: ".$e->getMessage() . "-trace: ".$e->getTraceAsString());
            }

            $i++;
        }

        $deleteEmptyBeschikbaarheid = "DELETE FROM `dagbesteding_beschikbaarheid` WHERE `maandagVan` IS NULL AND `maandagTot` IS NULL AND `dinsdagVan`IS NULL AND `dinsdagTot`IS NULL AND `woensdagVan`IS NULL AND `woensdagTot`IS NULL AND `donderdagVan`IS NULL AND `donderdagTot`IS NULL AND `vrijdagVan`IS NULL AND `vrijdagTot`IS NULL AND `zaterdagVan`IS NULL AND `zaterdagTot`IS NULL AND `zondagVan`IS NULL AND `zondagTot` IS NULL";
        $connection = $this->em->getConnection();
        $connection->executeQuery($deleteEmptyBeschikbaarheid);

        return 0;
    }

    /**
     * @param Deelnemer $scipDeelnemer
     */
    private function addDeelnemer($scipDeelnemer)
    {
        $dbDeelnemer = new \DagbestedingBundle\Entity\Deelnemer();
        $dbDeelnemer->setMedewerker($scipDeelnemer->getMedewerker() ?? $this->defaultMedewerker);
        $dbDeelnemer->setAanmelddatum($scipDeelnemer->getCreated());
        $dbDeelnemer->setKlant($scipDeelnemer->getKlant());
        $dbDeelnemer->setRisDossiernummer($scipDeelnemer->getRisNummer());


        $this->migrateDeelnemer($scipDeelnemer,$dbDeelnemer);

        return $dbDeelnemer;

    }

//        $this->output->writeln("Reden '{$this->redenPattern}' is niet uniek!");

    /**
     * @param Deelnemer $scipDeelnemer
     * @param \DagbestedingBundle\Entity\Deelnemer $dbDeelnemer
     * @param $output
     * @return mixed
     */
    private function migrateDeelnemer($scipDeelnemer, $dbDeelnemer)
    {


        //migreer RIS nummer alleen als die niet al aanwezig is.
        if($r = $dbDeelnemer->getRisDossiernummer() ?? $scipDeelnemer->getRisNummer()) $dbDeelnemer->setRisDossiernummer($r);


        foreach($scipDeelnemer->getDocumenten() as $d)
        {
            $newD = new Document();
            $newD->setNaam($d->getNaam());
            $newD->setFilename($d->getFilename());
            $newD->setFile($d->getFile());
            $newD->setMedewerker($d->getMedewerker());

            //toestemmingsformulier migreren obv deze fantastische wijze...
            if(strpos($d->getNaam(), 'oestemming') !== false || strpos($d->getNaam(), "ennismaking") !== false)
            {
                $toestemming = new \AppBundle\Entity\Toestemmingsformulier();
                $toestemming->setFilename($d->getFilename());
                $toestemming->setFile($d->getFile());
                $toestemming->setMedewerker($d->getMedewerker());
                $dbDeelnemer->getKlant()->setToestemmingsformulier($toestemming);

            }


            switch($d->getType())
            {
                case \ScipBundle\Entity\Document::TYPE_OVEREENKOMST:
                    $newD->setNaam(\ScipBundle\Entity\Document::TYPE_OVEREENKOMST);
                    break;
                case \ScipBundle\Entity\Document::TYPE_VOG:
                    $newD->setNaam(\ScipBundle\Entity\Document::TYPE_VOG);
                    break;
            }


            $dbDeelnemer->addDocument($newD);
            $this->output->writeln("Add document ".$newD->getNaam());
        }

        if(null === $scipDeelnemer->getProjecten()) {
            //Geen proojecten, verslagen wel migreren.
            foreach($scipDeelnemer->getVerslagen() as $verslag)
            {
                $dbVerslag = new Verslag();
                $dbVerslag->setMedewerker($verslag->getMedewerker());
                $dbVerslag->setOpmerking($verslag->getTekst());
                $dbVerslag->setDatum($verslag->getDatum());
                $dbDeelnemer->addVerslag($dbVerslag);
            }
           $this->output->writeln("SCIP deelnemer without projecten. Only verslagen added.: ".$scipDeelnemer->getKlant()->getNaam());
            return $dbDeelnemer;

        }


        $this->output->writeln("Create traject. ".$scipDeelnemer->getKlant()->getNaam());

        $dbTraject = new Traject();
        //mappen
        if($t = $scipDeelnemer->getEvaluatiedatum() ?? false) $dbTraject->setEvaluatiedatum($t);
        $dbTraject->setResultaatgebiedsoort($this->defaultResultaatgebiedSoort);
        $trajectSoort = ($scipDeelnemer->getType() == Deelnemer::TYPE_WMO) ? $this->wmoTrajectsoort : $this->defaultTrajectsoort;
        $dbTraject->setSoort($trajectSoort);
        $dbTraject->setStartdatum($dbDeelnemer->getAanmelddatum());
        $dbTraject->setTrajectcoach($this->defaultTrajectcoach);


        $dbDeelnemer->addTraject($dbTraject);

       $this->output->writeln("Add verslagen");
        foreach($scipDeelnemer->getVerslagen() as $verslag)
        {
            $dbVerslag = new Verslag();
            $dbVerslag->setMedewerker($verslag->getMedewerker());
            $dbVerslag->setOpmerking($verslag->getTekst());
            $dbVerslag->setDatum($verslag->getDatum());
            $dbTraject->addVerslag($dbVerslag);
        }


        $this->output->writeln("Add werkdoelen");

        foreach($scipDeelnemer->getWerkdoelen() as $werkdoel)
        {
            $dbWerkdoel = new Werkdoel();
            $dbWerkdoel->setDatum($werkdoel->getDatum());
            $dbWerkdoel->setTekst($werkdoel->getTekst());
            $dbWerkdoel->setMedewerker($werkdoel->getMedewerker());
            $dbTraject->addWerkdoel($dbWerkdoel);
        }
        /** @var \ScipBundle\Entity\Project $project */
        foreach($scipDeelnemer->getProjecten() as $project)
        {
           $this->output->writeln("Add project: ".$project->getNaam());

            $dbProject = $this->dbProjects[strtoupper($project->getNaam())];
            $dbTraject->addProject($dbProject);
            if($locatie = $this->getLocationForProject($dbProject))
            {
                $dbTraject->addLocatie($locatie);
            }

            $dbTraject->setTrajectcoach($this->getTrajectcoachForProject($dbProject)?:$this->defaultTrajectcoach);
        }

        foreach($scipDeelnemer->getDeelnames() as $deelname)
        {
           $this->output->writeln("Add deelname for project: ". $deelname->getProject()->getNaam());
            $dbDeelname = new Deelname();

            $beschikbaarheid = $deelname->getBeschikbaarheid();
            if($beschikbaarheid == null) continue;

            $dbBeschikbaarheid = new Beschikbaarheid();
            $dbBeschikbaarheid->setMaandagTot(($beschikbaarheid->getMaandagTot()));
            $dbBeschikbaarheid->setDinsdagTot(($beschikbaarheid->getDinsdagTot()));
            $dbBeschikbaarheid->setWoensdagTot(($beschikbaarheid->getWoensdagTot()));
            $dbBeschikbaarheid->setDonderdagTot(($beschikbaarheid->getDonderdagTot()));
            $dbBeschikbaarheid->setVrijdagTot(($beschikbaarheid->getVrijdagTot()));
            $dbBeschikbaarheid->setZaterdagTot(($beschikbaarheid->getZaterdagTot()));
            $dbBeschikbaarheid->setZondagTot(($beschikbaarheid->getZondagTot()));

            $dbBeschikbaarheid->setMaandagVan(($beschikbaarheid->getMaandagVan()));
            $dbBeschikbaarheid->setDinsdagVan(($beschikbaarheid->getDinsdagVan()));
            $dbBeschikbaarheid->setWoensdagVan(($beschikbaarheid->getWoensdagVan()));
            $dbBeschikbaarheid->setDonderdagVan(($beschikbaarheid->getDonderdagVan()));
            $dbBeschikbaarheid->setVrijdagVan(($beschikbaarheid->getVrijdagVan()));
            $dbBeschikbaarheid->setZaterdagVan(($beschikbaarheid->getZaterdagVan()));
            $dbBeschikbaarheid->setZondagVan(($beschikbaarheid->getZondagVan()));

            $dbDeelname->setBeschikbaarheid($dbBeschikbaarheid);
            $dbDeelname->setTraject($dbTraject);

            $dbProject = $this->dbProjects[strtoupper($deelname->getProject()->getNaam())];
            $dbDeelname->setProject($dbProject);

            $dbTraject->addDeelname($dbDeelname);

        }
        return $dbDeelnemer;
    }

    private function mapAndCreateProjects()
    {
        $dbProjectRep = $this->em->getRepository(Project::class);

        $scipProjectRep = $this->em->getRepository(\ScipBundle\Entity\Project::class);

       $this->output->writeln("Map SCIP projects to DB projects");
        //map existing projects
        foreach($dbProjectRep->findAll() as $project)
        {
            $this->dbProjects[strtoupper($project->getNaam())] = $project;
        }

        //create non-existing projects
        foreach($scipProjectRep->findAll() as $project)
        {
            if(!isset($this->dbProjects[strtoupper($project->getNaam() )]) )
            {
                $dbProject = new Project();
                $dbProject->setNaam($project->getNaam());
                $dbProject->setActief($project->isActief());
                $dbProject->setKpl($project->getKpl());


                (!$this->input->getOption('dry-run')) ? $this->dbProjectenDao->create($dbProject):null;

               $this->output->writeln("Create DB Project: ".$dbProject->getNaam());
                $this->dbProjects[strtoupper($dbProject->getNaam() )] = $dbProject;
            }
        }

        /**
         * Change case for keys.
         */
        foreach($this->projectLocations as $k=>$v)
        {
            $K = strtoupper($k);
            unset($this->projectLocations[$k]);
            $this->projectLocations[$K] = $v;
        }

        foreach($this->projectTrajectcoaches as $k=>$v)
        {
            $K = strtoupper($k);
            unset($this->projectTrajectcoaches[$k]);
            $this->projectTrajectcoaches[$K] = $v;
        }

        foreach($this->dbProjects as $projectName => $dbProject)
        {
            $locationName = $this->projectLocations[$projectName] ?? false;

            $trajectcoachName = $this->projectTrajectcoaches[$projectName]  ?? false;

            if($locationName) {
                $locatie = $this->locatieRep->findOneBy(["naam"=>$locationName]);
                if(!$locatie instanceof Locatie) {
                    $locatie = new Locatie();
                    $locatie->setActief(true);
                    $locatie->setNaam($locationName);
                    $this->locatieDao->create($locatie);

                }
                $this->projectLocations[$projectName]=$locatie;
            }
            else {
                $this->output->writeln("Cannot find location for project: ".$projectName);
            }

            if($trajectcoachName)
            {
               $trajectcoach = $this->trajectCoachRep->findOneBy(["displayName"=>$trajectcoachName]);
                if(!$trajectcoach instanceof Trajectcoach) {

                   // unset($this->projectTrajectcoaches[$projectName]);
                    $trajectcoach = new Trajectcoach();
                    $trajectcoach->setNaam($trajectcoachName);
                    $trajectcoach->setActief(true);
                    $this->trajectcoachDao->create($trajectcoach);

                }
                $this->projectTrajectcoaches[$projectName]=$trajectcoach;
            }
            else {
                $this->output->writeln("Cannot find trajectcoach for project: ".$projectName);
            }

        }
    }

    private function getLocationForProject($dbProject)
    {
        return $this->projectLocations[strtoupper($dbProject->getNaam())]??false;
    }

    private function getTrajectcoachForProject($dbProject)
    {
        return $this->projectTrajectcoaches[strtoupper($dbProject->getNaam())]??false;
    }
}
