<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not execute the SQL, just show what would be done')
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'Enable debug mode with detailed logging')
            ->addOption('no-sql-output', null, InputOption::VALUE_NONE, 'Do not output the full SQL in logs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $debug = $input->getOption('debug');
        $noSqlOutput = $input->getOption('no-sql-output');

        $startTime = new \DateTime();
        $timeFormatted = $startTime->format('Y-m-d H:i:s');

        $io->writeln("[{$timeFormatted}] SQL execution started");

        $file = realpath($input->getArgument('file'));
        if (!$file) {
            $io->error("[{$timeFormatted}] File not found!");
            throw new \InvalidArgumentException('File not found!');
        }

        $io->writeln("[{$timeFormatted}] Reading SQL from: " . $file);
        $sql = file_get_contents($file);

        if (!$noSqlOutput) {
            $io->writeln("[{$timeFormatted}] SQL content:");
            $io->writeln($sql);
        } else {
            $io->writeln("[{$timeFormatted}] SQL content loaded (output suppressed)");
            // Show SQL size for reference
            $io->writeln("[{$timeFormatted}] SQL size: " . strlen($sql) . " bytes");
        }

        if ($input->getOption('dry-run')) {
            $io->writeln("[{$timeFormatted}] Dry run mode - SQL not executed");
            $io->success("[{$timeFormatted}] Dry run completed!");
            return 0;
        }

        try {
            $io->writeln("[{$timeFormatted}] Executing SQL...");

            /* @var $connection Connection */
            $connection = $this->entityManager->getConnection();

            // Track execution time
            $execStartTime = microtime(true);

            // Execute the SQL query
            $connection->executeQuery($sql);

            $execEndTime = microtime(true);
            $execDuration = round($execEndTime - $execStartTime, 2);

            $endTime = new \DateTime();
            $endTimeFormatted = $endTime->format('Y-m-d H:i:s');

            $io->writeln("[{$endTimeFormatted}] SQL execution completed in {$execDuration} seconds");

            // Additional debug information (safer version)
            if ($debug) {
                $io->writeln("[{$endTimeFormatted}] Debug information:");

                // Try to extract table names from SQL (basic approach)
                preg_match_all('/CREATE\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?\s+`?(\w+)`?/i', $sql, $createdTables);

                // Also look for DROP statements
                preg_match_all('/DROP\s+TABLE\s+IF\s+EXISTS\s+`?(\w+)`?/i', $sql, $droppedTables);

                // Safer approach: check existence of tables without requiring special privileges
                if (!empty($createdTables[1])) {
                    $io->writeln("[{$endTimeFormatted}] Tables potentially created/modified:");

                    foreach ($createdTables[1] as $table) {
                        // Safe existence check
                        try {
                            // First check if table exists in a permission-safe way
                            // This will throw an exception if the table doesn't exist
                            $connection->executeQuery("SELECT 1 FROM `{$table}` LIMIT 0");

                            // If we get here, table exists, so try to count rows
                            try {
                                $count = $connection->executeQuery("SELECT COUNT(*) as cnt FROM `{$table}`")->fetchOne();
                                $io->writeln("[{$endTimeFormatted}] - {$table}: Exists with approximately {$count} rows");
                            } catch (\Exception $e) {
                                // If COUNT fails but table exists, likely a permission issue
                                $io->writeln("[{$endTimeFormatted}] - {$table}: Exists (row count unavailable)");
                            }
                        } catch (\Exception $e) {
                            $io->writeln("[{$endTimeFormatted}] - {$table}: Does not exist or cannot be accessed");
                        }
                    }
                }

                // Basic information that should work with minimal permissions
                try {
                    // Most basic server information without requiring special privileges
                    $connection->executeQuery("SELECT 1");
                    $io->writeln("[{$endTimeFormatted}] Database connection is working");
                } catch (\Exception $e) {
                    $io->writeln("[{$endTimeFormatted}] Database connection issue: " . $e->getMessage());
                }
            }

            $io->success("[{$endTimeFormatted}] Done!");
            return 0;

        } catch (\Exception $e) {
            $errorTime = new \DateTime();
            $errorTimeFormatted = $errorTime->format('Y-m-d H:i:s');

            $io->error("[{$errorTimeFormatted}] Error executing SQL: " . $e->getMessage());

            if ($debug) {
                $io->writeln("[{$errorTimeFormatted}] Stack trace:");
                $io->writeln($e->getTraceAsString());

                // Safe basic error checking
                $io->writeln("[{$errorTimeFormatted}] Checking SQL for potential issues:");

                // Check for syntax that might be problematic
                if (preg_match('/CREATE\s+TABLE.*ENGINE\s*=\s*(\w+)/i', $sql, $matches)) {
                    $io->writeln("[{$errorTimeFormatted}] - SQL uses storage engine: " . $matches[1]);
                }

                if (stripos($sql, 'TEMPORARY') !== false) {
                    $io->writeln("[{$errorTimeFormatted}] - SQL contains TEMPORARY table declarations");
                }

                if (stripos($sql, 'LOCK TABLES') !== false) {
                    $io->writeln("[{$errorTimeFormatted}] - SQL contains LOCK TABLES statements which might cause issues");
                }

                // Look for potentially problematic patterns
                if (preg_match_all('/ALTER\s+TABLE.*ADD\s+(PRIMARY\s+KEY|UNIQUE|INDEX|FOREIGN\s+KEY)/i', $sql, $matches)) {
                    $io->writeln("[{$errorTimeFormatted}] - SQL contains " . count($matches[0]) . " index/key modifications");
                }
            }

            return 1;
        }
    }
}