<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunSqlCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        ini_set('memory_limit', '512M');
        $this
            ->setName('app:run-sql')
            ->setHelp('Executes the given SQL-script.')
            ->addArgument('file', InputArgument::REQUIRED, 'SQL-file')
            ->addOption('dry-run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = realpath($input->getArgument('file'));
        if (!$file) {
            throw new \InvalidArgumentException('File not found!');
        }

        $sql = file_get_contents($file);

        $output->writeln('Running SQL...');
        $output->writeln($sql);

        if (!$input->getOption('dry-run')) {
            /* @var $connection Connection */
            $connection = $this->entityManager->getConnection();
            $connection->executeQuery($sql);
        }

        $output->writeln('Done!');

        return 0;
    }
}
