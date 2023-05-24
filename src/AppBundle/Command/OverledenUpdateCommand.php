<?php

namespace AppBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OverledenUpdateCommand extends \Symfony\Component\Console\Command\Command
{

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:overleden:update');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $klanten = $this->em->getRepository(Klant::class)->createQueryBuilder('klant')
            ->where('klant.overleden = TRUE')
            ->andWhere('klant.geenPost IS NULL OR klant.geenPost = FALSE OR klant.geenEmail IS NULL OR klant.geenEmail = FALSE')
            ->getQuery()
            ->getResult();

        $output->writeln('Updating '.(is_array($klanten) || $klanten instanceof \Countable ? count($klanten) : 0).' clients...');

        foreach ($klanten as $klant) {
            /* @var Klant $klant */
            $klant->setGeenPost(true)->setGeenEmail(true);
        }

        $this->em->flush();

        $output->writeln('Finished!');
        return 0;
    }
}
