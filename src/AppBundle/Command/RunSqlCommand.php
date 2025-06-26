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

            // Execute the SQL query - ENHANCED VERSION
            // Split into statements for better error reporting
            $statements = $this->splitSqlStatements($sql);
            $io->writeln("[{$timeFormatted}] Found " . count($statements) . " SQL statements to execute");

            foreach ($statements as $index => $statement) {
                $statementTime = new \DateTime();
                $statementTimeFormatted = $statementTime->format('Y-m-d H:i:s');

                $io->writeln("[{$statementTimeFormatted}] Executing statement " . ($index + 1) . " of " . count($statements));

                if ($debug && !$noSqlOutput) {
                    $io->writeln("[{$statementTimeFormatted}] Statement content:");
                    $io->writeln($statement);
                }

                try {
                    // Execute the statement
                    $connection->executeQuery($statement);

                    // Check for warnings
                    $warnings = $connection->executeQuery("SHOW WARNINGS");
                    $warningsData = $warnings->fetchAllAssociative();

                    if (count($warningsData) > 0) {
                        $io->warning("[{$statementTimeFormatted}] SQL warnings detected:");
                        foreach ($warningsData as $warning) {
                            $io->writeln("[{$statementTimeFormatted}] {$warning['Level']}: ({$warning['Code']}) {$warning['Message']}");
                        }
                    }

                    // Check if a table was created
                    if (preg_match('/CREATE\s+(?:OR\s+REPLACE\s+)?TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`\'"]?(\w+)[`\'"]?/i', $statement, $matches)) {
                        $tableName = $matches[1];
                        $tableExists = $connection->executeQuery(
                            "SELECT COUNT(*) FROM information_schema.tables WHERE table_name = ?",
                            [$tableName]
                        )->fetchOne();

                        if ($tableExists) {
                            $io->writeln("[{$statementTimeFormatted}] Table {$tableName} created/updated successfully");

                            // Get row count for confirmation
                            try {
                                $rowCount = $connection->executeQuery("SELECT COUNT(*) FROM `{$tableName}`")->fetchOne();
                                $io->writeln("[{$statementTimeFormatted}] Table {$tableName} contains {$rowCount} rows");
                            } catch (\Exception $e) {
                                $io->writeln("[{$statementTimeFormatted}] Could not count rows in {$tableName}: " . $e->getMessage());
                            }
                        } else {
                            $io->warning("[{$statementTimeFormatted}] Table {$tableName} was not created/updated!");
                        }
                    }

                } catch (\Exception $e) {
                    $io->error("[{$statementTimeFormatted}] Error executing statement " . ($index + 1) . ": " . $e->getMessage());

                    if ($debug) {
                        $io->writeln("[{$statementTimeFormatted}] Error class: " . get_class($e));
                        $io->writeln("[{$statementTimeFormatted}] Error code: " . $e->getCode());
                        $io->writeln("[{$statementTimeFormatted}] Failed statement:");
                        $io->writeln($statement);
                    }

                    return 1;
                }
            }

            $execEndTime = microtime(true);
            $execDuration = round($execEndTime - $execStartTime, 2);

            $endTime = new \DateTime();
            $endTimeFormatted = $endTime->format('Y-m-d H:i:s');

            $io->writeln("[{$endTimeFormatted}] SQL execution completed in {$execDuration} seconds");

            // Additional debug information (safer version)
            if ($debug) {
                // Your existing debug code
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

    /**
     * Split SQL string into individual statements
     */
    private function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $currentStatement = '';
        $delimiter = ';';
        $inString = false;
        $stringChar = '';
        $inComment = false;
        $commentType = '';

        $lines = explode("\n", $sql);

        foreach ($lines as $line) {
            // Check for DELIMITER changes
            if (preg_match('/^DELIMITER\s+(.+)$/i', trim($line), $matches)) {
                if (!empty($currentStatement)) {
                    $statements[] = $currentStatement;
                    $currentStatement = '';
                }
                $delimiter = $matches[1];
                continue;
            }

            $currentStatement .= $line . "\n";

            // Check if this statement is complete
            if (substr(rtrim($line), -strlen($delimiter)) === $delimiter) {
                // Make sure it's not inside a string or comment
                $statementToCheck = trim($currentStatement);

                // Very simplified check - a proper implementation would need to track
                // strings and comments throughout the statement
                if (substr_count($statementToCheck, "'") % 2 === 0 &&
                    substr_count($statementToCheck, "\"") % 2 === 0 &&
                    !preg_match('~/\*(?!.*\*/)~s', $statementToCheck)) {

                    $statements[] = $currentStatement;
                    $currentStatement = '';
                }
            }
        }

        // Add any remaining statement
        if (!empty(trim($currentStatement))) {
            $statements[] = $currentStatement;
        }

        return $statements;
    }
}