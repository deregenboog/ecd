<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Locatie;
use InloopBundle\Filter\KlantFilter;
use InloopBundle\Filter\LocatieFilter;
use InloopBundle\Strategy\AmocStrategy;
use InloopBundle\Strategy\GebruikersruimteStrategy;
use InloopBundle\Strategy\VerblijfsstatusStrategy;
use Knp\Component\Pager\PaginatorInterface;

class AccessUpdater
{
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

    public function __construct(
        EntityManager $em,
        KlantDaoInterface $klantDao,
        LocatieDaoInterface $locatieDao
    ) {
        $this->em = $em;
        $this->klantDao = $klantDao;
        $this->locatieDao = $locatieDao;
    }

    public function updateAll()
    {
        foreach ($this->getLocations() as $locatie) {
            $this->updateForLocation($locatie);
        }
    }

    public function updateForLocation(Locatie $locatie)
    {
        $wasEnabled = $this->em->getFilters()->isEnabled('overleden');
        $this->em->getFilters()->enable('overleden');

        // @todo move to container service
        $strategies = [
            new GebruikersruimteStrategy(),
            new AmocStrategy(),
            new VerblijfsstatusStrategy(),
        ];

        foreach ($strategies as $strategy) {
            if ($strategy->supports($locatie)) {
                $filter = new KlantFilter($strategy);
                break;
            }
        }

        if (!isset($filter)) {
            throw new \LogicException('No supported strategy found!');
        }

        $builder = $this->klantDao->getAllQueryBuilder($filter);
        $klantIds = array_map(function ($klantId) {
            return $klantId['id'];
        }, $builder->select('klant.id')->distinct(true)->getQuery()->getResult());

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

        if (!$wasEnabled) {
            $this->em->getFilters()->disable('overleden');
        }
    }

    public function updateForClient(Klant $klant)
    {
        $wasEnabled = $this->em->getFilters()->isEnabled('overleden');
        $this->em->getFilters()->enable('overleden');

        foreach ($this->getLocations() as $locatie) {
            // @todo move to container service
            $strategies = [
                new GebruikersruimteStrategy(),
                new AmocStrategy(),
                new VerblijfsstatusStrategy(),
            ];

            foreach ($strategies as $strategy) {
                if ($strategy->supports($locatie)) {
                    $filter = new KlantFilter($strategy);
                    break;
                }
            }

            if (!isset($filter)) {
                throw new \LogicException('No supported strategy found!');
            }

            $this->em->getConnection()->executeQuery('DELETE FROM inloop_toegang
                WHERE locatie_id = :locatie AND klant_id = :klant', [
                    'locatie' => $locatie->getId(),
                    'klant' => $klant->getId(),
                ], [
                    'locatie' => ParameterType::INTEGER,
                    'klant' => ParameterType::INTEGER,
                ]);

            $this->em->getConnection()->executeQuery('INSERT INTO inloop_toegang (klant_id, locatie_id)
                SELECT id, :locatie FROM klanten
                WHERE id = :klant
                AND id NOT IN (SELECT klant_id FROM inloop_toegang WHERE locatie_id = :locatie)', [
                    'locatie' => $locatie->getId(),
                    'klant' => $klant->getId(),
                ], [
                    'locatie' => ParameterType::INTEGER,
                    'klant' => ParameterType::INTEGER,
                ]);
        }

        if (!$wasEnabled) {
            $this->em->getFilters()->disable('overleden');
        }
    }

    private function getLocations()
    {
        $filter = new LocatieFilter();
        $filter->actief = true;

        return $this->locatieDao->findAll(null, $filter);
    }
}
