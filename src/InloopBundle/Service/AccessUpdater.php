<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Filter\KlantFilter;
use InloopBundle\Filter\LocatieFilter;
use InloopBundle\Strategy\AmocStrategy;
use InloopBundle\Strategy\GebruikersruimteStrategy;
use InloopBundle\Strategy\OndroBongStrategy;
use InloopBundle\Strategy\VerblijfsstatusStrategy;

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

        $filter = new KlantFilter($this->getStrategy($locatie));
        $builder = $this->klantDao->getAllQueryBuilder($filter);
        $klantIds = $this->getKlantIds($builder);

        $params = [
            'locatie' => $locatie->getId(),
            'klanten' => $klantIds,
        ];
        $types = [
            'locatie' => ParameterType::INTEGER,
            'klanten' => Connection::PARAM_INT_ARRAY,
        ];

        $this->em->getConnection()->executeQuery('DELETE FROM inloop_toegang
            WHERE locatie_id = :locatie AND klant_id NOT IN (:klanten)', $params, $types);

        $this->em->getConnection()->executeQuery('INSERT INTO inloop_toegang (klant_id, locatie_id)
            SELECT id, :locatie FROM klanten
            WHERE id IN (:klanten)
            AND id NOT IN (SELECT klant_id FROM inloop_toegang WHERE locatie_id = :locatie)', $params, $types);

        if (!$wasEnabled) {
            $this->em->getFilters()->disable('overleden');
        }
    }

    public function updateForClient(Klant $klant)
    {
        $wasEnabled = $this->em->getFilters()->isEnabled('overleden');
        $this->em->getFilters()->enable('overleden');

        foreach ($this->getLocations() as $locatie) {
            $filter = new KlantFilter($this->getStrategy($locatie));
            $builder = $this->klantDao->getAllQueryBuilder($filter);
            $builder
                ->andWhere('klant.id = :klant_id')
                ->setParameter('klant_id', $klant->getId())
            ;

//            echo $builder->getQuery()->getSQL();
//            echo "\n";

            $klantIds = $this->getKlantIds($builder);

            $params = [
                'locatie' => $locatie->getId(),
                'klant' => $klant->getId(),
            ];
            $types = [
                'locatie' => ParameterType::INTEGER,
                'klant' => ParameterType::INTEGER,
            ];

            if (in_array($klant->getId(), $klantIds)) {
                $this->em->getConnection()->executeQuery('INSERT IGNORE INTO inloop_toegang (klant_id, locatie_id)
                    VALUES (:klant, :locatie)', $params, $types);
            } else {
                $this->em->getConnection()->executeQuery('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie AND klant_id = :klant', $params, $types);
            }
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

    private function getStrategy(Locatie $locatie)
    {
        // @todo move to container service
        /**
         * Let op:
         * De volgorde is niet willekeurig:
         * Hij selecteert de eerste strategie die door een locatie wordt ondersteund
         * In theorie kan dat tegenstrijdigheden opleveren (iets is gebruikersruimte en amoc bv)
         * maar in de praktijk werkt dit niet zo:
         * ze sluiten elkaar eigenlijk altijd uit.
         *
         * De eerste strategie voor een locatie bepaalt of er toegang wordt verleend de klant.
         */
        $strategies = [
            new GebruikersruimteStrategy(),
            new AmocStrategy(),
            new OndroBongStrategy(),
            new VerblijfsstatusStrategy(),
        ];

        foreach ($strategies as $strategy) {
            if ($strategy->supports($locatie)) {
                return $strategy;
            }

        }

        throw new \LogicException('No supported strategy found!');
    }

    private function getKlantIds(QueryBuilder $builder)
    {
        $klantIdArray =  $builder->select('klant.id')->distinct(true)->getQuery()->getResult();
        return array_map(
            function ($klantId) {
                return $klantId['id'];
            },$klantIdArray

        );
    }
}
