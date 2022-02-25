<?php

namespace PfoBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;
use PfoBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStadsdeelPostcodegebiedCommand extends \Symfony\Component\Console\Command\Command
{
    protected function configure()
    {
        $this
            ->setName('pfo:stadsdeel:update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $clienten = $em->getRepository(Client::class)->createQueryBuilder('client')
            ->where('client.werkgebied IS NULL')
            ->getQuery()
            ->getResult();

        $output->writeln('Updating '.(is_array($clienten) || $clienten instanceof \Countable ? count($clienten) : 0).' clients...');

        foreach ($clienten as $client) {
            /* @var Client $client */
            $client->koppelPostcodeWerkgebied($em);
            $output->writeln("Update ".$client->getNaam());
        }

        $em->flush();

        $output->writeln('Finished!');
        return 0;
    }
}
