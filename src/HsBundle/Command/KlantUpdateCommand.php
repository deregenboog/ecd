<?php

namespace HsBundle\Command;

use Doctrine\ORM\EntityManager;
use HsBundle\Entity\Klus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class KlantUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('hs:klant:update');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $entityManager EntityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // find klussen
        $klussen = $entityManager->getRepository(Klus::class)->createQueryBuilder('klus')
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
            $entityManager->flush();
        }
        $output->writeln(sprintf('%d klussen bijgewerkt', count($klussen)));

        // find klussen
        $klussen = $entityManager->getRepository(Klus::class)->createQueryBuilder('klus')
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
            $entityManager->flush();
        }
        $output->writeln(sprintf('%d on hold klussen open gezet', count($klussen)));

    }
}
