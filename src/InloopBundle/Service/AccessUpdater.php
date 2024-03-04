<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Entity\Locatie;
use InloopBundle\Filter\KlantFilter;

class AccessUpdater
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var KlantDao
     */
    private $klantDao;

    /**
     * @var LocatieDao
     */
    private $locatieDao;

    private $debug = false;

    private iterable $strategies;

    /**
     * The access updater works as follows:
     *
     * It works based on LOCATION. Not on KLANT.
     * So, it checks for a location which klanten are allowed,
     * based on certain fields of first intake.
     *
     * So, if only access for a certain KLANT should be refreshed, it acts the same way, although the query will be restricted
     * to specific klantId.
     *
     * The strategies used are combined in an AND manner ... uitzoeken.
     */
    public function __construct(
        EntityManagerInterface $em,
        KlantDao $klantDao,
        LocatieDao $locatieDao,
        iterable $strategies
    )
    {
        $this->em = $em;
        $this->klantDao = $klantDao;
        $this->locatieDao = $locatieDao;
        $this->strategies = $strategies;
    }

    public function updateAll()
    {
        $this->em->getConnection()->beginTransaction();
        $this->em->getConnection()->executeQuery('DELETE FROM inloop_toegang');

        $locaties = $this->locatieDao->findAllActiveLocationsOfTypes(['Inloop', 'Nachtopvang']);
        foreach ($locaties as $locatie) {
            $this->updateForLocation($locatie);
        }
        $this->em->getConnection()->commit();
    }

    public function updateForLocation(Locatie $locatie)
    {
        // enable filter and store original state
        $wasEnabled = $this->em->getFilters()->isEnabled('overleden');
        $this->em->getFilters()->enable('overleden');

        // get clients that are granted access
        $klantIds = $this->getKlantIds($locatie);

        $params = [
            'locatie' => $locatie->getId(),
            'klanten' => $klantIds,
        ];
        $types = [
            'locatie' => ParameterType::INTEGER,
            'klanten' => Connection::PARAM_INT_ARRAY,
        ];

        if($locatie->isActief()) {
//            dump($params);
            $this->em->getConnection()->executeQuery('DELETE FROM inloop_toegang
                WHERE locatie_id = :locatie AND klant_id NOT IN (:klanten)', $params, $types);

            $this->em->getConnection()->executeQuery('INSERT INTO inloop_toegang (klant_id, locatie_id)
            SELECT id, :locatie FROM klanten
            WHERE id IN (:klanten)
            AND id NOT IN (SELECT klant_id FROM inloop_toegang WHERE locatie_id = :locatie)', $params, $types);
        }
        else // locatie gesloten. geen toegang.
        {
            $this->em->getConnection()->executeQuery('DELETE FROM inloop_toegang
                WHERE locatie_id = :locatie',['locatie'=>$locatie->getId()],['locatie'=>ParameterType::INTEGER]);
        }

        if (!$wasEnabled) {
            $this->em->getFilters()->disable('overleden');
        }
    }

    public function updateForClient(Klant $klant)
    {
        $wasEnabled = $this->em->getFilters()->isEnabled('overleden');
        $this->em->getFilters()->enable('overleden');
        $this->log("Updating access for ".$klant->getNaam());

        $locaties = $this->locatieDao->findAllActiveLocationsOfTypes(['Inloop', 'Nachtopvang']);
        foreach ($locaties as $locatie) {
            $this->log($locatie);

            // get clients that are granted access
            $klantIds = $this->getKlantIds($locatie, $klant);

            $params = [
                'locatie' => $locatie->getId(),
                'klant' => $klant->getId(),
            ];
            $types = [
                'locatie' => ParameterType::INTEGER,
                'klant' => ParameterType::INTEGER,
            ];

            if (in_array($klant->getId(), $klantIds)) {
                $this->log("Access granted");
                try {
                    $this->em->getConnection()->executeQuery('INSERT INTO inloop_toegang (klant_id, locatie_id)
                    VALUES (:klant, :locatie)', $params, $types);
                }catch(\Exception $e)
                {
                    // do nothing
                    //
                }

            } else {
                $this->log("Access denied");
                $this->em->getConnection()->executeQuery('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie AND klant_id = :klant', $params, $types);
            }
        }

        if (!$wasEnabled) {
            $this->em->getFilters()->disable('overleden');
        }
    }

    private function getSupportedStrategies(Locatie $locatie)
    {
        /**
         * Let op:
         * De volgorde is niet willekeurig en daarmee restrictief
         * Hij selecteert de eerste strategie die door een locatie wordt ondersteund
         * In theorie kan dat tegenstrijdigheden opleveren (iets is gebruikersruimte en amoc bv)
         * maar in de praktijk werkt dit niet zo:
         * ze sluiten elkaar eigenlijk altijd uit.
         *
         * De eerste strategie voor een locatie bepaalt of er toegang wordt verleend de klant.
         * Dwz: strategie is van toepassing op de locatie(s), dan wordt er alleen mogelijk toegang verleend tot die locatie
         * Dus locatie is mutual exclusive. De eerste strategie die geldt voor de locatie, is de gene die bepaald op klant toegang heeft.
         *
         * Dat werkt dus niet wanneer een strategie in theorie geldt voor alle mogelijke loacties,
         * en alleen op basis van de query bepaald kan worden of iemand wel of geen toegang heeft.
         *
         * Dus moet er gestapeld kunnen worden.
         */

        $supportedStrategies = [];

        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($locatie)) {
                $supportedStrategies[] = $strategy;
            }
        }

        if (count($supportedStrategies) < 1) {
            throw new \LogicException('No supported strategy found!');
        }

        return $supportedStrategies;
    }

    private function getKlantIds(Locatie $locatie, Klant $klant = null)
    {
        // build query for finding clients
        $builder = $this->klantDao->getAllQueryBuilder();
        $builder->select('klant.id')->distinct(true);

        foreach ($this->getSupportedStrategies($locatie) as $strategy) {
            $strategy->buildQuery($builder, $locatie);
        }
        $builder->andWhere(sprintf('status INSTANCE OF %s', Aanmelding::class)); // only active clients

        // limit to client, if provided
        if ($klant instanceof Klant) {
            $builder->andWhere('klant.id = :klant_id')
                ->setParameter('klant_id', $klant->getId());
        }

        return array_map(
            function ($klantId) {
                return $klantId['id'];
            }, $builder->getQuery()->getResult()
        );
    }

    private function log($msg)
    {
        if($this->debug == true)
        {
            echo $msg."\n";
        }
    }
}
