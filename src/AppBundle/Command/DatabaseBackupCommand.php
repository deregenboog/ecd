<?php

namespace AppBundle\Command;

use App\Kernel;
use BackupManager\Compressors\CompressorProvider;
use BackupManager\Compressors\GzipCompressor;
use BackupManager\Compressors\NullCompressor;
use BackupManager\Config\Config;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Databases\MysqlDatabase;
use BackupManager\Filesystems\Destination;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\Filesystems\LocalFilesystem;
use BackupManager\Manager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;

class DatabaseBackupCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel, Connection $connection)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
        $this->connection = $connection;
        parent::__construct();
    }

    protected function configure()
    {
        ini_set("memory_limit","512M");
        $this
            ->setName('app:database:backup')
            ->addOption('keep', 'k', InputOption::VALUE_OPTIONAL, 'Number of backups to keep', 5)
            ->addOption('exclude-logs', 'x', InputOption::VALUE_NONE, 'Exclude logs-tables')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $backupDir = $this->kernel->getProjectDir().'/backups';

        if (!realpath($backupDir)) {
            $output->writeln($backupDir.' does not exist!');

            return 0;
        }

        $backupDir = realpath($backupDir);
        if (!is_writable($backupDir)) {
            $output->writeln($backupDir.' is not writable!');

            return 0;
        }

        $ignoreTables = [];
        if ($input->getOption('exclude-logs')) {
            $ignoreTables = [
                'logs_2011',
                'logs_2012',
                'logs_2013',
                'logs_2014',
                'logs_2015',
                'logs_2016',
                'logs_2017',
                'logs',
            ];
        }

        $connection = $this->connection;
        $dbConfig = new Config([
            'mysql' => [
                'type' => $connection->getDatabasePlatform()->getName(),
                'host' => $connection->getHost(),
                'port' => $connection->getPort(),
                'user' => $connection->getUsername(),
                'pass' => $connection->getPassword(),
                'database' => $connection->getDatabase(),
                'singleTransaction' => true,
                'ignoreTables' => $ignoreTables,
            ],
        ]);
        $fsConfig = new Config([
            'local' => [
                'type' => 'Local',
                'root' => $backupDir,
            ],
        ]);

        // build providers
        $filesystems = new FilesystemProvider($fsConfig);
        $filesystems->add(new LocalFilesystem());

        $databases = new DatabaseProvider($dbConfig);
        $databases->add(new MysqlDatabase());

        $compressors = new CompressorProvider();
//        $compressors->add(new GzipCompressor());
        $compressors->add(new NullCompressor());

        $filename = sprintf('%s-%s.sql', $connection->getDatabase(), (new \DateTime())->format('YmdHis'));
        $output->writeln('Backing up MySQL database to '.$backupDir.'/'.$filename);

        $manager = new Manager($filesystems, $databases, $compressors);
        $manager->makeBackup()->run('mysql', [new Destination('local', $filename)], 'null');

        $output->writeln(sprintf('Keeping %d newest backups', $input->getOption('keep')));
        $command = ['ls', '-tp', '|', 'grep', '-v', '\'/$\'', '|', 'tail', '-n', '+%d', '|', 'xargs', '-I', '{}', 'rm', '--', '{}'];
        $process = new Process($command, $backupDir);
        $process->run();
        return 0;
    }
}
