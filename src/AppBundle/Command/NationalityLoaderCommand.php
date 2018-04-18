<?php

namespace AppBundle\Command;

use AppBundle\Entity\Nationaliteit;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NationalityLoaderCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function configure()
    {
        $this
            ->setName('app:nationality:load')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'Input file', 'app/data/nationaliteiten.csv')
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
            $nationaliteit = new Nationaliteit($values[0]);
            $this->entityManager->persist($nationaliteit);
            ++$i;
        }
        $this->entityManager->flush();
        fclose($handle);

        $output->writeln($i.' nationaliteiten opgeslagen');
    }
}
