<?php

namespace OekraineBundle\Service;

use AppBundle\Entity\Klant;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Aanmelding;
use OekraineBundle\Entity\Bezoeker;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Filter\BezoekerFilter;
use OekraineBundle\Filter\LocatieFilter;
use OekraineBundle\Strategy\AmocStrategy;
use OekraineBundle\Strategy\GebruikersruimteStrategy;
use OekraineBundle\Strategy\OndroBongStrategy;
use OekraineBundle\Strategy\VerblijfsstatusStrategy;

class AccessUpdater
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var BezoekerDaoInterface
     */
    private $bezoekerDao;

    /**
     * @var LocatieDaoInterface
     */
    private $locatieDao;

    private $debug = false;

    public function __construct(
        EntityManagerInterface $em,
        BezoekerDaoInterface $bezoekerDao,
        LocatieDaoInterface $locatieDao
    ) {
        $this->em = $em;
        $this->bezoekerDao = $bezoekerDao;
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

        $filter = new BezoekerFilter($this->getStrategy($locatie));
        $filter->huidigeStatus = Aanmelding::class; // alleen klanten met inloopdossier mogen toegang

        $builder = $this->bezoekerDao->getAllQueryBuilder($filter);
        $klantIds = $this->getKlantIds($builder);

        $params = [
            'locatie' => $locatie->getId(),
            'klanten' => $klantIds,
        ];
        $types = [
            'locatie' => ParameterType::INTEGER,
            'klanten' => Connection::PARAM_INT_ARRAY,
        ];

        if ($locatie->isActief()) {
            $this->em->getConnection()->executeQuery('DELETE FROM oekraine_toegang
                WHERE locatie_id = :locatie AND bezoeker_id NOT IN (:klanten)', $params, $types);

            $this->em->getConnection()->executeQuery('INSERT INTO oekraine_toegang (bezoeker_id, locatie_id)
            SELECT id, :locatie FROM klanten
            WHERE id IN (:klanten)
            AND id NOT IN (SELECT bezoeker_id FROM oekraine_toegang WHERE locatie_id = :locatie)', $params, $types);
        } else { // locatie gesloten. geen toegang.
            $this->em->getConnection()->executeQuery('DELETE FROM oekraine_toegang
                WHERE locatie_id = :locatie', ['locatie' => $locatie->getId()], ['locatie' => ParameterType::INTEGER]);
        }

        if (!$wasEnabled) {
            $this->em->getFilters()->disable('overleden');
        }
    }

    public function updateForClient(Bezoeker $bezoeker)
    {
        $wasEnabled = $this->em->getFilters()->isEnabled('overleden');
        $this->em->getFilters()->enable('overleden');
        $this->log('Updating access for '.$bezoeker->getAppKlant()->getNaam());

        foreach ($this->getLocations() as $locatie) {
            $this->log($locatie);
            $strategy = $this->getStrategy($locatie);
            $this->log('Strategy used: '.get_class($strategy));

            $filter = new BezoekerFilter($strategy);
            $filter->huidigeStatus = Aanmelding::class; // alleen klanten met een inloopdossier mogen toegang.

            $builder = $this->bezoekerDao->getAllQueryBuilder($filter);
            $builder
                ->andWhere('bezoeker.id = :klant_id')
                ->setParameter('klant_id', $bezoeker->getId());

            //            $sql = $builder->getQuery()->getSQL();
            $this->log($builder->getQuery()->getSQL());

            $bezoekerIds = $this->getKlantIds($builder);

            $params = [
                'locatie' => $locatie->getId(),
                'klant' => $bezoeker->getId(),
            ];
            $types = [
                'locatie' => ParameterType::INTEGER,
                'klant' => ParameterType::INTEGER,
            ];

            if (in_array($bezoeker->getId(), $bezoekerIds)) {
                $this->log('Access granted');
                try {
                    $this->em->getConnection()->executeQuery('INSERT INTO oekraine_toegang (bezoeker_id, locatie_id)
                    VALUES (:klant, :locatie)', $params, $types);
                } catch (\Exception $e) {
                    // do nothing
                    //
                }
            } else {
                $this->log('Access denied');
                $this->em->getConnection()->executeQuery('DELETE FROM oekraine_toegang
                    WHERE locatie_id = :locatie AND bezoeker_id = :klant', $params, $types);
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
         * De volgorde is niet willekeurig en daarmee restrictief
         * Hij selecteert de eerste strategie die door een locatie wordt ondersteund
         * In theorie kan dat tegenstrijdigheden opleveren (iets is gebruikersruimte en amoc bv)
         * maar in de praktijk werkt dit niet zo:
         * ze sluiten elkaar eigenlijk altijd uit.
         *
         * De eerste strategie voor een locatie bepaalt of er toegang wordt verleend de klant.
         * Dwz: strategie is van toepassing op de locatie(s), dan wordt er alleen mogelijk toegang verleend tot die locaties, en niet tot andere locaties.
         * mogelijk = aan de hand van de gestelde criteria.
         * Dus locatie is mutual exclusive. De eerste strategie die geldt voor de locatie, is de gene die bepaald op klant toegang heeft.
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
        $klantIdArray = $builder->select('bezoeker.id')->distinct(true)->getQuery()->getResult();

        return array_map(
            function ($klantId) {
                return $klantId['id'];
            }, $klantIdArray
        );
    }

    private function log($msg)
    {
        if (true == $this->debug) {
            echo $msg."\n";
        }
    }
}
