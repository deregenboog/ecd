<?php

namespace AppBundle\Command;

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
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseBackupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:database:backup');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $backupDir = $this->getContainer()->get('kernel')->getRootDir().'/../backups';
        if (!realpath($backupDir)) {
            $output->writeln($backupDir.' does not exist!');

            return;
        }

        $backupDir = realpath($backupDir);
        if (!is_writable($backupDir)) {
            $output->writeln($backupDir.' is not writable!');

            return;
        }

        /* @var $connection \Doctrine\DBAL\Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();
        $dbConfig = new Config([
            'mysql' => [
                'type' => $connection->getDatabasePlatform()->getName(),
                'host' => $connection->getHost(),
                'port' => $connection->getPort(),
                'user' => $connection->getUsername(),
                'pass' => $connection->getPassword(),
                'database' => $connection->getDatabase(),
                'singleTransaction' => true,
                'ignoreTables' => ['logs'],
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
        $compressors->add(new GzipCompressor());
        $compressors->add(new NullCompressor());

        $filename = sprintf('%s-%s.sql.gzip', $connection->getDatabase(), (new \DateTime())->format('YmdHis'));
        $output->writeln('Backing up MySQL database to '.$backupDir.'/'.$filename);

        $manager = new Manager($filesystems, $databases, $compressors);
        $manager->makeBackup()->run('mysql', [new Destination('local', $filename)], 'gzip');
    }
}
