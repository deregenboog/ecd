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

class ZipcodeLoaderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:database:load-zipcodes')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'Input file', 'app/data/postcodes.csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getOption('file');

        /* @var EntityManager $manager */
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $batchSize = 1000;

        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('File %s does not exist', $file));
        }

        if (!($handle = fopen($file, 'r'))) {
            throw new \InvalidArgumentException(sprintf('Cannot open file %s', $file));
        }

        $output->writeln(sprintf('Lezen bestand %s', $file));

        $i = 0;
        while ($values = fgetcsv($handle, 0, ';')) {
            $manager->persist(new Postcode($values[0], $values[1], $values[2]));
            if (++$i % $batchSize === 0) {
                $manager->flush();
            }
        }
        $manager->flush();
        fclose($handle);

        $output->writeln($i.' postcodes opgeslagen');
    }
}
