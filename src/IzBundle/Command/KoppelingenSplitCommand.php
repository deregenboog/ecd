<?php

namespace IzBundle\Command;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManagerInterface;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Koppeling;
use IzBundle\Entity\Verslag;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

class KoppelingenSplitCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Medewerker
     */
    private $admin;

    /**
     * @var string
     */
    private $host;

    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('iz:koppelingen:split')
            ->addArgument('host', InputArgument::OPTIONAL, 'Host for creating links', 'http://localhost')
            ->addOption('count-only')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->admin = $this->em->getRepository(Medewerker::class)->findOneBy(['username' => 'bhuttinga']);
        $this->host = $input->getArgument('host');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $builder = $this->em->getRepository(Hulpvraag::class)->createQueryBuilder('hulpvraag')
            ->innerJoin('hulpvraag.hulpaanbod', 'hulpaanbod')
            ->where('hulpvraag.koppelingStartdatum <= :start')
            ->andWhere('hulpvraag.koppelingEinddatum >= :eind')
            ->andWhere('DATEDIFF(hulpvraag.koppelingEinddatum, hulpvraag.koppelingStartdatum) >= 243')
            ->setParameters([
                'start' => new \DateTime('2017-10-01'),
                'eind' => new \DateTime('2018-05-01'),
            ])
        ;
        $hulpvragen = $builder->getQuery()->getResult();

        if ($input->getOption('count-only')) {
            $tableData = [];
            foreach ($hulpvragen as $hulpvraag) {
                $tableData[] = [
                    $hulpvraag->getId(),
                    $hulpvraag->getKoppelingStartdatum()->format('d-m-Y'),
                    $hulpvraag->getKoppelingEinddatum()->format('d-m-Y'),
                    $this->getDays($hulpvraag),
                    $this->createLink($hulpvraag),
                ];
            }

            $table = new Table($output);
            $table
                ->setHeaders(['ID', 'Startdatum', 'Einddatum', 'Dagen', 'URL'])
                ->setRows($tableData)
            ;
            $table->render();

            $output->writeln('Totaal: '.(is_array($hulpvragen) || $hulpvragen instanceof \Countable ? count($hulpvragen) : 0));

            return 0;
        }

        $tableData = [];
        foreach ($hulpvragen as $hulpvraag) {
            [$koppeling, $newKoppeling] = $this->split($hulpvraag->getKoppeling());

            $this->addEndMessage($koppeling);
            $this->addStartMessage($newKoppeling);

            $tableData[] = [
                $this->createLink($koppeling->getHulpvraag()),
                $this->createLink($newKoppeling->getHulpvraag()),
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Oud', 'Nieuw'])
            ->setRows($tableData)
        ;
        $table->render();
        $output->writeln('Totaal: '.(is_array($hulpvragen) || $hulpvragen instanceof \Countable ? count($hulpvragen) : 0));

        return 0;
    }

    private function addEndMessage(Koppeling $koppeling)
    {
        $verslag = new Verslag();
        $verslag
            ->setOpmerking('Automatisch beëindigd en nieuwe koppeling aangemaakt omdat de koppeling langer dan 6 maanden liep.')
            ->setIzDeelnemer($koppeling->getHulpvraag()->getIzKlant())
            ->setKoppeling($koppeling->getHulpvraag())
            ->setMedewerker($this->admin)
        ;
        $this->em->persist($verslag);
        $this->em->flush();

        $sql = sprintf("UPDATE iz_verslagen SET created = '%s' WHERE id = %d",
            $koppeling->getEinddatum()->format('Y-m-d H:i:s'),
            $verslag->getId()
        );
        $this->em->getConnection()->query($sql);
    }

    private function addStartMessage(Koppeling $newKoppeling)
    {
        $verslag = new Verslag();
        $verslag
            ->setOpmerking('Automatisch aangemaakt omdat de koppeling langer dan 6 maanden liep.')
            ->setIzDeelnemer($newKoppeling->getHulpvraag()->getIzKlant())
            ->setKoppeling($newKoppeling->getHulpvraag())
            ->setMedewerker($this->admin)
        ;
        $this->em->persist($verslag);
        $this->em->flush();

        $sql = sprintf("UPDATE iz_verslagen SET created = '%s' WHERE id = %d",
            $newKoppeling->getStartdatum()->format('Y-m-d H:i:s'),
            $verslag->getId()
        );
        $this->em->getConnection()->query($sql);
    }

    private function split(Koppeling $koppeling)
    {
        $newStartdatum = (clone $koppeling->getStartdatum())->modify('+6 months');
        if ($newStartdatum < new \DateTime('2018-03-01')) {
            $newStartdatum = new \DateTime('2018-03-01');
        }
        if ($newStartdatum > new \DateTime('2018-04-01')) {
            $newStartdatum = new \DateTime('2018-04-01');
        }

        $oldEinddatum = (clone $newStartdatum)->modify('-1 second');
        $newTussenevaludatiedatum = (clone $newStartdatum)->modify('+3 months');
        $newEindevaludatiedatum = (clone $newStartdatum)->modify('+3 months');

        $hulpvraag = $koppeling->getHulpvraag();
        $hulpaanbod = $koppeling->getHulpaanbod();

        $newHulpvraag = new Hulpvraag();
        $newHulpvraag
            // start
            ->setStartdatum($newStartdatum)
            // algemeen
            ->setIzKlant($hulpvraag->getIzKlant())
            ->setDeelnemer($hulpvraag->getIzKlant())
            ->setMedewerker($hulpvraag->getMedewerker());
        if ($hulpvraag->getDoelgroep()) {
            $newHulpvraag->setDoelgroep($hulpvraag->getDoelgroep());
        }
        if ($hulpvraag->getHulpvraagsoort()) {
            $newHulpvraag->setHulpvraagsoort($hulpvraag->getHulpvraagsoort());
        }
        $newHulpvraag
            ->setProject($hulpvraag->getProject())
            // afsluiting
            ->setEinddatum($hulpvraag->getEinddatum())
            ->setSuccesindicatorenFinancieel($hulpvraag->getSuccesindicatorenFinancieel())
            ->setSuccesindicatorenParticipatie($hulpvraag->getSuccesindicatorenParticipatie())
            ->setSuccesindicatorenPersoonlijk($hulpvraag->getSuccesindicatorenPersoonlijk());
        if ($hulpvraag->getEindeVraagAanbod()) {
            $newHulpvraag->setEindeVraagAanbod($hulpvraag->getEindeVraagAanbod());
        }
        $newHulpvraag
            // matching
            ->setDagdeel($hulpvraag->getDagdeel())
            ->setGeschiktVoorExpat($hulpvraag->isGeschiktVoorExpat())
            ->setVoorkeurGeslacht($hulpvraag->getVoorkeurGeslacht())
            ->setInfo($hulpvraag->getInfo())
        ;

        $newHulpaanbod = new Hulpaanbod();
        $newHulpaanbod
            // start
            ->setStartdatum($newStartdatum)
            // algemeen
            ->setIzVrijwilliger($hulpaanbod->getIzVrijwilliger())
            ->setMedewerker($hulpaanbod->getMedewerker())
            ->setDoelgroepen($hulpaanbod->getDoelgroepen())
            ->setHulpvraagsoorten($hulpaanbod->getHulpvraagsoorten())
            ->setProject($hulpaanbod->getProject())
            // afsluiting
            ->setEinddatum($hulpaanbod->getEinddatum());
        if ($hulpaanbod->getEindeVraagAanbod()) {
            $newHulpaanbod->setEindeVraagAanbod($hulpaanbod->getEindeVraagAanbod());
        }
        $newHulpaanbod
            // matching
            ->setDagdeel($hulpaanbod->getDagdeel())
            ->setExpat($hulpaanbod->isExpat())
            ->setVoorkeurGeslacht($hulpaanbod->getVoorkeurGeslacht())
            ->setInfo($hulpaanbod->getInfo())
        ;

        // nieuwe koppeling maken met nieuwe hulpvraag en nieuw hulpaanbod
        $newKoppeling = Koppeling::create($newHulpvraag, $newHulpaanbod);
        $newKoppeling
            ->setStartdatum($newStartdatum)
            ->setEinddatum($koppeling->getEinddatum())
            ->setTussenevaluatiedatum($newTussenevaludatiedatum)
            ->setEindevaluatiedatum($newEindevaludatiedatum)
            ->setAfsluitreden($koppeling->getAfsluitreden())
            ->setSuccesvol($koppeling->isSuccesvol())
        ;

        // einddatum oude koppeling wijzigen
        $koppeling->setEinddatum($oldEinddatum);

        // verslagen verplaatsen naar nieuwe hulpvraag als ze binnen het
        // datumbereik van de nieuwe hulpvraag vallen
        foreach ($hulpvraag->getVerslagen(false) as $verslag) {
            if ($verslag->getCreated() >= $newStartdatum) {
                $verslag->setKoppeling($newHulpvraag);
            }
        }

        // verslagen verplaatsen naar nieuw hulpaanbod als ze binnen het
        // datumbereik van de nieuw hulpaanbod vallen
        foreach ($hulpaanbod->getVerslagen(false) as $verslag) {
            if ($verslag->getCreated() >= $newStartdatum) {
                $verslag->setKoppeling($newHulpaanbod);
            }
        }

        $this->em->persist($newHulpvraag);
        $this->em->persist($newHulpaanbod);
        $this->em->flush();

        return [$koppeling, $newKoppeling];
    }

    private function getDays(Hulpvraag $koppeling)
    {
        return $koppeling->getKoppelingEinddatum()->diff($koppeling->getKoppelingStartdatum())->days;
    }

    private function createLink(Hulpvraag $hulpvraag)
    {
        return $this->host.$this->router->generate('iz_koppelingen_view', ['id' => $hulpvraag->getId()]);
    }
}
