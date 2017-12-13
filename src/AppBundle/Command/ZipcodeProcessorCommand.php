<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Stadsdeel;
use AppBundle\Entity\Postcode;
use Symfony\Component\Console\Input\InputOption;

class ZipcodeProcessorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:zipcode:process')
            ->addArgument('files', InputArgument::REQUIRED + InputArgument::IS_ARRAY, 'Files to process')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output file', 'app/data/postcodes.csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = $input->getArgument('files');
        $outputFile = $input->getOption('output');

        $data = [];
        $postcodes = [];
        $conflicten = [];
        foreach ($files as $file) {
            if (!file_exists($file)) {
                throw new \InvalidArgumentException(sprintf('File %s does not exist', $file));
            }

            if (!($handle = fopen($file, 'r'))) {
                throw new \InvalidArgumentException(sprintf('Cannot open file %s', $file));
            }

            $output->writeln(sprintf('Lezen bestand %s', $file));

            if (!isset($headers)) {
                $headers = $row = fgetcsv($handle, 0, ';');
            } elseif ($headers !== fgetcsv($handle, 0, ';')) {
                throw new \RuntimeException(sprintf('Structure of file %s does not match previous files', $file));
            }

            while ($row = fgetcsv($handle, 0, ';')) {
                $data[] = $row;
            }

            fclose($handle);
        }

        $output->writeln(sprintf('Verwerken %d adressen', count($data)));

        foreach ($data as $row) {
            $postcode = $row[array_search('Postcode', $headers)];
            $stadsdeel = $row[array_search('Naam stadsdeel', $headers)];
            $postcodegebied = $row[array_search('Naam gebiedsgerichtwerkengebied', $headers)];

            if (!$postcode || in_array($postcode, $conflicten)) {
                continue;
            }

            if (array_key_exists($postcode, $postcodes) && $postcodes[$postcode] != [$stadsdeel, $postcodegebied]) {
                $conflicten[] = $postcode;
                unset($postcodes[$postcode]);
                if ($input->getOption('verbose')) {
                    $output->writeln('Conflict voor postcode '.$postcode);
                }
                continue;
            }

            $postcodes[$postcode] = [$stadsdeel, $postcodegebied];
        }

        $output->writeln(sprintf('Oplossen %d conflicterende postcodes', count($conflicten)));

        $count = [];
        foreach ($data as $row) {
            $stadsdeel = $row[array_search('Naam stadsdeel', $headers)];
            $postcodegebied = $row[array_search('Naam gebiedsgerichtwerkengebied', $headers)];
            $postcode = $row[array_search('Postcode', $headers)];

            if (!$postcode || !in_array($postcode, $conflicten)) {
                continue;
            }

            $count[$postcode][] = $stadsdeel.' | '.$postcodegebied;
        }

        foreach ($count as $postcode => $values) {
            $values = array_count_values($values);
            arsort($values);

            foreach ($values as $key => $weight) {
                list($stadsdeel, $postcodegebied) = explode(' | ', $key);
                if ($stadsdeel && $postcodegebied) {
                    $postcodes[$postcode] = [$stadsdeel, $postcodegebied];
                    break;
                }
            }
        }

        $output->writeln(sprintf('Bestand %s openen', $outputFile));

        $handle = fopen($outputFile, 'w');
        foreach ($postcodes as $postcode => $values) {
            fputcsv($handle, array_merge([$postcode], $values), ';');
        }
        fclose($handle);

        $output->writeln(sprintf('%d postcodes opgeslagen', count($postcodes)));
    }
}
