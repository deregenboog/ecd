<?php

namespace HsBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use HsBundle\Entity\Klus;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class KlantUpdateCommand extends \Symfony\Component\Console\Command\Command
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('hs:klant:update');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // find klussen
        $klussen = $this->em->getRepository(Klus::class)->createQueryBuilder('klus')
            ->where('klus.einddatum <= :today')
            ->andWhere('klus.status != :status_afgerond')
            ->setParameter('today', new \DateTime('today'))
            ->setParameter('status_afgerond', Klus::STATUS_AFGEROND)
            ->getQuery()
            ->getResult()
        ;

        foreach ($klussen as $klus) {
            /* @var $klus Klus */
            // trigger status update
            $klus->setEinddatum($klus->getEinddatum());
            $this->em->flush();
        }
        $output->writeln(sprintf('%d klussen bijgewerkt', is_array($klussen) || $klussen instanceof \Countable ? count($klussen) : 0));

        // find klussen
        $klussen = $this->em->getRepository(Klus::class)->createQueryBuilder('klus')
            ->where('klus.onHoldTot <= :today')
            ->andWhere('klus.status = :status_on_hold')
            ->setParameter('today', new \DateTime('today'))
            ->setParameter('status_on_hold', Klus::STATUS_ON_HOLD)
            ->getQuery()
            ->getResult()
        ;

        foreach ($klussen as $klus) {
            /* @var $klus Klus */
            // trigger status update
            $klus->setOnHold(false);
            $klus->setOnHoldTot(null);
            $this->em->flush();
        }
        $output->writeln(sprintf('%d on hold klussen open gezet', is_array($klussen) || $klussen instanceof \Countable ? count($klussen) : 0));

        return 0;
    }
}
