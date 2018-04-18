<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CountryProcessorCommand extends ContainerAwareCommand
{
    const SOURCE_URI = 'https://nl.wikipedia.org/wiki/ISO_3166-1';

    protected function configure()
    {
        $this
            ->setName('app:country:process')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output file', 'app/data/landen.csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputFile = $input->getOption('output');

        $html = file_get_contents(self::SOURCE_URI);
        $dom = \DomDocument::loadHTML($html);

        $tables = $dom->getElementsByTagName('table');
        assert(1 === count($tables));
        $table = $tables->item(0);

        $data = [];
        $rows = $table->getElementsByTagName('tr');
        assert($rows->length > 200);
        $output->writeln(sprintf('Verwerken %d landen', $rows->length - 1));
        foreach ($rows as $i => $row) {
            if (0 === $i) {
                continue;
            }

            $cells = $row->getElementsByTagName('td');
            $name = $cells->item(0)->childNodes->item(2)->nodeValue;
            $iso2 = $cells->item(1)->nodeValue;
            $iso3 = $cells->item(2)->nodeValue;
            $data[] = [$name, $iso2, $iso3];
        }

        $output->writeln(sprintf('Bestand %s openen', $outputFile));
        $handle = fopen($outputFile, 'w');
        foreach ($data as $values) {
            fputcsv($handle, $values, ';');
        }
        fclose($handle);

        $output->writeln(sprintf('%d landen opgeslagen', count($data)));
    }
}
