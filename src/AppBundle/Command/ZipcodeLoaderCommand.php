<?php

namespace AppBundle\Command;

use AppBundle\Entity\GgwGebied;
use AppBundle\Entity\Postcode;
use AppBundle\Entity\Werkgebied;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ZipcodeLoaderCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @var array
     */
    private $cache = [];

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:zipcode:load')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'Input file', 'app/data/postcodes.csv')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->batchSize = 1000;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getOption('file');

        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('File %s does not exist', $file));
        }

        if (!($handle = fopen($file, 'r'))) {
            throw new \InvalidArgumentException(sprintf('Cannot open file %s', $file));
        }

        $output->writeln(sprintf('Lezen bestand %s', $file));

        $i = 0;
        while ($values = fgetcsv($handle, 0, ';')) {
            $stadsdeel = $this->getWerkgebied($values[1]);

            $postcodegebied = null;
            if ('' !== $values[2]) {
                $postcodegebied = $this->getGgwGebied($values[2]);
            }

            if ($stadsdeel && $postcodegebied) {
                $this->entityManager->persist(new Postcode($values[0], $stadsdeel, $postcodegebied));
            } elseif ($stadsdeel) {
                $this->entityManager->persist(new Postcode($values[0], $stadsdeel));
            }

            if (0 === ++$i % $this->batchSize) {
                $this->entityManager->flush();
            }
        }
        $this->entityManager->flush();
        fclose($handle);

        //        $this->postLoad();

        $output->writeln($i.' postcodes opgeslagen');

        return 0;
    }

    private function getWerkgebied($name)
    {
        if (!isset($this->cache['werkgebieden'][$name])) {
            $this->cache['werkgebieden'][$name] = $this->entityManager->find(Werkgebied::class, $name);
        }
        if (!isset($this->cache['werkgebieden'][$name])) {
            $werkgebied = new Werkgebied($name);
            $this->entityManager->persist($werkgebied);
            $this->cache['werkgebieden'][$name] = $werkgebied;
        }

        return $this->cache['werkgebieden'][$name];
    }

    private function getGgwGebied($name)
    {
        if (!isset($this->cache['ggw_gebieden'][$name])) {
            $this->cache['ggw_gebieden'][$name] = $this->entityManager->find(GgwGebied::class, $name);
        }
        if (!isset($this->cache['ggw_gebieden'][$name])) {
            $ggwGebied = new GgwGebied($name);
            $this->entityManager->persist($ggwGebied);
            $this->cache['ggw_gebieden'][$name] = $ggwGebied;
        }

        return $this->cache['ggw_gebieden'][$name];
    }

    private function postLoad()
    {
        $queries = [
            "INSERT IGNORE ggw_gebieden (naam) VALUES ('Noord-Oost');",
            "INSERT IGNORE ggw_gebieden (naam) VALUES ('Noord-West');",

            "UPDATE postcodes SET postcodegebied = 'Noord-Oost' WHERE postcodegebied = 'Oost';",
            "UPDATE postcodes SET postcodegebied = 'Noord-West' WHERE postcodegebied = 'West';",

            "UPDATE klanten SET postcodegebied = 'Noord-Oost' WHERE postcodegebied = 'Oost';",
            "UPDATE klanten SET postcodegebied = 'Noord-West' WHERE postcodegebied = 'West';",

            "UPDATE vrijwilligers SET postcodegebied = 'Noord-Oost' WHERE postcodegebied = 'Oost';",
            "UPDATE vrijwilligers SET postcodegebied = 'Noord-West' WHERE postcodegebied = 'West';",

            "UPDATE clip_clienten SET postcodegebied = 'Noord-Oost' WHERE postcodegebied = 'Oost';",
            "UPDATE clip_clienten SET postcodegebied = 'Noord-West' WHERE postcodegebied = 'West';",

            "UPDATE hs_klanten SET postcodegebied = 'Noord-Oost' WHERE postcodegebied = 'Oost';",
            "UPDATE hs_klanten SET postcodegebied = 'Noord-West' WHERE postcodegebied = 'West';",

            "DELETE FROM ggw_gebieden WHERE naam = 'Oost';",
            "DELETE FROM ggw_gebieden WHERE naam = 'West';",
        ];
        foreach ($queries as $sql) {
            $this->entityManager->getConnection()->query($sql);
        }
    }
}
