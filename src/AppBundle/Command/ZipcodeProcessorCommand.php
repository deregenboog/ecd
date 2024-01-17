<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ZipcodeProcessorCommand extends Command
{
    /**
     *
     * Postcodes verkrijgen vai download op
     * https://data.amsterdam.nl/data/bag/adressen/?modus=volledig
     * Voor amsterdam en Weesp.
     *
     * Diemen, amstelveen
     */
    protected function configure()
    {
        ini_set('auto_detect_line_endings',TRUE);
        $this
            ->setName('app:zipcode:process')
            ->addArgument('files', InputArgument::REQUIRED + InputArgument::IS_ARRAY, 'Files to process')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output file', 'app/data/postcodes.csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('Mem usage at start: %s', memory_get_usage()/1048576));
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
            $output->writeln(sprintf('Mem usage before loop: %s', memory_get_usage()/1048576));

            $i=0;
            while ($row = fgetcsv($handle, 0, ';')) {
                if(!isset($row)) break 1;


//                $data[] = $row; // huge memory consumption; direct link to fields works better.
                $data[] = [$row[4], $row[6],$row[8]];

                if(($i % 1000) == 1){
                    $output->writeln(sprintf('Mem usage (%d): %s', $i,memory_get_usage()/1048576));
//                    var_dump($row);
                }


                $i++;
            }

            fclose($handle);
        }

        $output->writeln(sprintf('Verwerken %d adressen', count($data)));

        foreach ($data as $row) {
//            $postcode = $row[array_search('Postcode', $headers)];
//            $stadsdeel = $row[array_search('Naam stadsdeel', $headers)];
//            $postcodegebied = $row[array_search('Naam gebiedsgerichtwerkengebied', $headers)];

            $postcode = $row[0];
            $stadsdeel = $row[1];
            $postcodegebied = $row[2];

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

            $postcodes[]= [$postcode, $stadsdeel, $postcodegebied];
        }
        $output->writeln(sprintf("Aantal postcodes: %s",count($postcodes)));

        $output->writeln(sprintf('Oplossen %d conflicterende postcodes', count($conflicten)));

        //OK alle conflicterenden zijn eruit gehaald en apart gezet.

        $count = [];
//        foreach ($data as $row) {
////            $stadsdeel = $row[array_search('Naam stadsdeel', $headers)];
////            $postcodegebied = $row[array_search('Naam gebiedsgerichtwerkengebied', $headers)];
////            $postcode = $row[array_search('Postcode', $headers)];
//
//            $postcode = $row[0];
//            $stadsdeel = $row[1];
//            $postcodegebied = $row[2];
//
//            if (!$postcode || !in_array($postcode, $conflicten)) {
//                continue;
//            }
//
//            /**
//             * Soort van samenvoegen en hieronder weer uit elkaar trekken? Sorteren , want..?
//             *
//             */
//            //$count[$postcode][] = $stadsdeel.' | '.$postcodegebied;
//
//            //meteen als multidim array
//            $count[$postcode][] = [$stadsdeel,$postcodegebied];
//
//        }

        /**
         * Ik snap dit niet. waarom?
         */

//        foreach ($count as $postcode => $values) {
//            $values = array_count_values($values);
//            arsort($values);
//
//            foreach ($values as $key => $weight) {
//                list($stadsdeel, $postcodegebied) = explode(' | ', $key);
//                if ($stadsdeel && $postcodegebied) {
//                    $postcodes[$postcode] = [$stadsdeel, $postcodegebied];
//                    break;
//                }
//            }
//        }

        $output->writeln(sprintf('Bestand %s openen', $outputFile));

        $handle = fopen($outputFile, 'w');
        foreach ($postcodes as $postcode => $values) {
//            fputcsv($handle, array_merge([$postcode], $values), ';');
            fputcsv($handle, $values, ';');
        }
        fclose($handle);

        $output->writeln(sprintf('%d postcodes opgeslagen', count($postcodes)));
        return 0;
    }
}
