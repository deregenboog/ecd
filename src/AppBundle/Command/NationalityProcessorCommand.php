<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NationalityProcessorCommand extends ContainerAwareCommand
{
    const SOURCE_URI = 'https://opendata.cbs.nl/ODataApi/odata/03743/Nationaliteiten';

    protected function configure()
    {
        $this
            ->setName('app:nationality:process')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output file', 'app/data/nationaliteiten.csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputFile = $input->getOption('output');

        $json = file_get_contents(self::SOURCE_URI);
        $rows = json_decode($json)->value;
        assert(count($rows) > 200);

        $totals = ['T001059', 'NAT9267', 'NAT9489', 'NAT9269', 'NAT9270', 'NAT9271', 'NAT9272', 'NAT9274', 'NAT9275'];

        $data = [];
        $output->writeln(sprintf('Verwerken %d nationaliteiten', count($rows)));
        foreach ($rows as $i => $row) {
            if (in_array($row->Key, $totals)) {
                continue;
            }
            $data[] = [$row->Title];
        }

        $output->writeln(sprintf('Bestand %s openen', $outputFile));
        $handle = fopen($outputFile, 'w');
        foreach ($data as $values) {
            fputcsv($handle, $values, ';');
        }
        fclose($handle);

        $output->writeln(sprintf('%d nationaliteiten opgeslagen', count($data)));
    }
}
