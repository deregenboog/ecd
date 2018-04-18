<?php

namespace AppBundle\Command;

use AppBundle\Entity\Land;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CountryLoaderCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function configure()
    {
        $this
            ->setName('app:country:load')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'Input file', 'app/data/landen.csv')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
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
            $land = new Land($values[0], $values[1], $values[2]);
            $this->entityManager->persist($land);
            ++$i;
        }
        $this->entityManager->flush();
        fclose($handle);

        $output->writeln($i.' landen opgeslagen');
    }
}
