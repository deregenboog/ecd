<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Fixes characters like this Ã« (ë), Ã© (é) and Ã¨ (è) caused by UTF8-encoding
 * characters that already are UTF8-encoded.
 */
class DatabaseFixEncodingCommand extends \Symfony\Component\Console\Command\Command
{
    protected function configure()
    {
        $this
            ->setName('app:database:fix-encoding')
            ->addArgument('table', InputArgument::REQUIRED)
            ->addArgument('field', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = $input->getArgument('table');
        $field = $input->getArgument('field');

        $replacements = [
            'Ä', 'Ë', 'Ï', 'Ö', 'Ü',
            'ä', 'ë', 'ï', 'ö', 'ü',
            'Á', 'É', 'Í', 'Ó', 'Ú',
            'á', 'é', 'í', 'ó', 'ú',
            'À', 'È', 'Ì', 'Ò', 'Ù',
            'à', 'è', 'ì', 'ò', 'ù',
            'Ç',
            'ç',
            'Ñ',
            'ñ',
        ];

//         // @link http://stackoverflow.com/questions/3371697/replacing-accented-characters-php
//         $replacements = [
//             'Š', 'š', 'Ž', 'ž',
//             'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ',
//             'Ç',
//             'È', 'É', 'Ê', 'Ë',
//             'Ì', 'Í', 'Î', 'Ï',
//             'Ñ',
//             'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø',
//             'Ù', 'Ú', 'Û', 'Ü',
//             'Ý',
//             'Þ', 'ß'=>'Ss',
//             'à', 'á', 'â', 'ã', 'ä', 'å', 'æ',
//             'ç',
//             'è', 'é', 'ê', 'ë',
//             'ì', 'í', 'î', 'ï',
//             'ð',
//             'ñ', 'ò', 'ó', 'ô', 'õ',
//             'ö', 'ø', 'ù', 'ú', 'û', 'ý', 'þ', 'ÿ'=>'y'
//         ];

        /* @var Connection $connection */
        $connection = $this->getContainer()->get('database_connection');
        foreach ($replacements as $replacement) {
            $search = utf8_encode($replacement);
            $query = "UPDATE `{$table}` SET `{$field}` = REPLACE(`{$field}`, '{$search}', '{$replacement}');";
            $numRows = $connection->executeUpdate($query, [$search, $replacement]);
            $output->writeln($query." ({$numRows} affected)");
        }
        return 0;
    }
}
