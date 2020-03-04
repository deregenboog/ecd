<?php

namespace PfoBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManagerInterface;
use PfoBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStadsdeelPostcodegebiedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pfo:stadsdeel:update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $clienten = $em->getRepository(Client::class)->createQueryBuilder('client')
            ->where('client.werkgebied IS NULL')
            ->getQuery()
            ->getResult();

        $output->writeln('Updating '.count($clienten).' clients...');

        foreach ($clienten as $client) {
            /* @var Client $client */
            $client->koppelPostcodeWerkgebied($em);
            $output->writeln("Update ".$client->getNaam());
        }

        $em->flush();

        $output->writeln('Finished!');
    }
}
