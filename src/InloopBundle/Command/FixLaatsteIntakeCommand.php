<?php

namespace InloopBundle\Command;

use AppBundle\Entity\Klant;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use InloopBundle\Entity\Intake;
use Doctrine\ORM\EntityManager;

class FixLaatsteIntakeCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $manager;

    protected function configure()
    {
        $this->setName('inloop:fix:laatste_intake');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $klanten = $this->getKlanten();
        $output->writeln(sprintf('%d klanten gevonden', count($klanten)));

        foreach ($klanten as $klant) {
            $laatsteIntake = $this->getLaatsteIntake($klant);

            if (!$klant->getLaatsteIntake()) {
                $output->writeln(sprintf(
                    'Laatste intake voor klant %d instellen op %d (was NULL)',
                    $klant->getId(),
                    $laatsteIntake->getId()
                ));
                $klant->setLaatsteIntake($laatsteIntake);
                break;
            } elseif ($klant->getLaatsteIntake()->getId() != $klant->getIntakes()[0]->getId()) {
                $output->writeln(sprintf(
                    'Laatste intake voor klant %d instellen op %d (was %d)',
                    $klant->getId(),
                    $laatsteIntake->getId(),
                    $klant->getLaatsteIntake()->getId()
                ));
                $klant->setLaatsteIntake($laatsteIntake);
                break;
            }
        }

        $this->manager->flush();

        $output->writeln('Succesvol afgerond');
    }

    private function getKlanten()
    {
        return $this->manager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->innerJoin('klant.intakes', 'intake')
            ->leftJoin('klant.laatsteIntake', 'laatsteIntake')
            ->groupBy('klant.id')
            ->getQuery()
            ->getResult()
        ;
    }

    private function getLaatsteIntake(Klant $klant)
    {
        $intakes = $this->manager->getRepository(Intake::class)->createQueryBuilder('intake')
            ->where('intake.klant = :klant')
            ->orderBy('intake.intakedatum', 'DESC')
            ->addOrderBy('intake.id', 'DESC')
            ->setParameter('klant', $klant)
            ->getQuery()
            ->setMaxResults(1)
            ->getResult()
        ;

        return $intakes[0];
    }
}
