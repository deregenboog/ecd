<?php

namespace PfoBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use PfoBundle\Entity\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStadsdeelPostcodegebiedCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('pfo:stadsdeel:update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var EntityManagerInterface $em */
        $clienten = $this->em->getRepository(Client::class)->createQueryBuilder('client')
            ->where('client.werkgebied IS NULL')
            ->getQuery()
            ->getResult();

        $output->writeln('Updating '.(is_array($clienten) || $clienten instanceof \Countable ? count($clienten) : 0).' clients...');

        foreach ($clienten as $client) {
            /* @var Client $client */
            $client->koppelPostcodeWerkgebied($this->em);
            $output->writeln('Update '.$client->getNaam());
        }

        $this->em->flush();

        $output->writeln('Finished!');

        return 0;
    }
}
