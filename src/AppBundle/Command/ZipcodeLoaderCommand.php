<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Postcodegebied;
use AppBundle\Entity\Stadsdeel;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Postcode;
use Symfony\Component\Console\Input\InputOption;
use AppBundle\Entity\Werkgebied;
use AppBundle\Entity\GgwGebied;

class ZipcodeLoaderCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
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

    protected function configure()
    {
        $this
            ->setName('app:zipcode:load')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'Input file', 'app/data/postcodes.csv')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->batchSize = 1000;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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
            $postcodegebied = $this->getGgwGebied($values[2]);

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

        $output->writeln($i.' postcodes opgeslagen');
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
}
