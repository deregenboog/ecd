<?php

namespace InloopBundle\Command;

use AppBundle\Service\KlantDaoInterface;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Toegang;
use InloopBundle\Filter\KlantFilter;
use InloopBundle\Service\LocatieDaoInterface;
use InloopBundle\Strategy\AmocStrategy;
use InloopBundle\Strategy\GebruikersruimteStrategy;
use InloopBundle\Strategy\VerblijfsstatusStrategy;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use InloopBundle\Filter\LocatieFilter;
use AppBundle\Entity\Klant;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

class UpdateAccessCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 10000;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var KlantDaoInterface
     */
    private $klantDao;

    /**
     * @var LocatieDaoInterface
     */
    private $locatieDao;

    protected function configure()
    {
        $this->setName('inloop:access:update');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->locatieDao = $this->getContainer()->get('InloopBundle\Service\LocatieDao');
        $this->klantDao = $this->getContainer()->get('AppBundle\Service\KlantDao');

        $this->em->getFilters()->enable('overleden');
        $this->klantDao->setItemsPerPage(self::BATCH_SIZE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filter = new LocatieFilter();
        $filter->actief = true;
        $locaties = $this->locatieDao->findAll(null, $filter);

        foreach ($locaties as $locatie) {
            $output->writeln('Updating access rules for location '.$locatie);

            // @todo move to container service
            $strategies = [
                new GebruikersruimteStrategy(),
                new AmocStrategy(),
                new VerblijfsstatusStrategy(),
            ];

            foreach ($strategies as $strategy) {
                if ($strategy->supports($locatie)) {
                    $output->writeln('Using strategy '.get_class($strategy));
                    $filter = new KlantFilter($strategy);
                    break;
                }
            }

            if (!isset($filter)) {
                throw new \LogicException('No supported strategy found!');
            }

            $builder = $this->klantDao->getAllQueryBuilder($filter);
            $klantIds = array_map(function($klantId) {
                return $klantId['id'];
            }, $builder->select('klant.id')->distinct(true)->getQuery()->getResult());

            $output->writeln('Processing '.count($klantIds).' clients');

            $this->em->getConnection()->executeQuery('DELETE FROM inloop_toegang
                WHERE locatie_id = :locatie AND klant_id NOT IN (:klanten)', [
                    'locatie' => $locatie->getId(),
                    'klanten' => $klantIds,
                ], [
                    'locatie' => ParameterType::INTEGER,
                    'klanten' => Connection::PARAM_INT_ARRAY,
                ]);

            $this->em->getConnection()->executeQuery('INSERT INTO inloop_toegang (klant_id, locatie_id)
                SELECT id, :locatie FROM klanten
                WHERE id IN (:klanten)
                AND id NOT IN (SELECT klant_id FROM inloop_toegang WHERE locatie_id = :locatie)', [
                    'locatie' => $locatie->getId(),
                    'klanten' => $klantIds,
                ], [
                    'locatie' => ParameterType::INTEGER,
                    'klanten' => Connection::PARAM_INT_ARRAY,
                ]);

//             $this->em->createQuery('DELETE FROM '.Toegang::class.' toegang
//                 WHERE toegang.klant IN (:klant_ids) AND toegang.locatie = :locatie')
//                 ->execute([
//                     'klant_ids' => $klantIds,
//                     'locatie' => $locatie,
//                 ]);

//             $i = 0;
//             foreach ($klantIds as $klantId) {
//                 $klant = $this->em->getReference(Klant::class, $klantId);
//                 $toegang = new Toegang($klant, $locatie);
//                 $this->em->persist($toegang);

//                 if (0 === ++$i % self::BATCH_SIZE) {
//                     $output->writeln('Saving batch');
//                     $this->em->flush();
//                 }
//             }

//             $output->writeln('Saving rest');
//             $this->em->flush();
        }

        $output->writeln('Finished!');
    }
}
