<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Entity\Locatie;
use InloopBundle\Filter\KlantFilter;
use InloopBundle\Filter\LocatieFilter;
use InloopBundle\Strategy\AmocStrategy;
use InloopBundle\Strategy\GebruikersruimteStrategy;
use InloopBundle\Strategy\IntakelocatieStrategy;
use InloopBundle\Strategy\OndroBongStrategy;
use InloopBundle\Strategy\SpecificLocationStrategy;
use InloopBundle\Strategy\ToegangOverigStrategy;

class AccessUpdater
{
    /**
     * @var EntityManager
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

    private $amoc_locaties = [];

    private $intake_locaties = [];

    private $amocVerblijfsstatus = "";


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

     *
     * @param EntityManager $em
     * @param KlantDao $klantDao
     * @param LocatieDao $locatieDao
     * @param $amoc_locaties
     * @param $intake_locaties
     */
    public function __construct(
        EntityManager $em,
        KlantDao $klantDao,
        LocatieDao $locatieDao,
        $amoc_locaties,
        $intake_locaties,
        $amocVerblijfsstatus
    )
    {
        $this->em = $em;
        $this->klantDao = $klantDao;
        $this->locatieDao = $locatieDao;
        $this->amoc_locaties = $amoc_locaties;
        $this->intake_locaties = $intake_locaties;
        $this->amocVerblijfsstatus = $amocVerblijfsstatus;

    }

    public function updateAll()
    {
        $this->em->getConnection()->beginTransaction();
        $this->em->getConnection()->executeQuery('DELETE FROM inloop_toegang');

        foreach ($this->getLocations() as $locatie) {
            $this->updateForLocation($locatie);
        }
        $this->em->getConnection()->commit();
    }

    public function updateForLocation(Locatie $locatie)
    {

        $wasEnabled = $this->em->getFilters()->isEnabled('overleden');

        $this->em->getFilters()->enable('overleden');

        $filter = new KlantFilter($this->getStrategies($locatie));
        $filter->huidigeStatus = Aanmelding::class; // alleen klanten met inloopdossier mogen toegang

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
        $inloopLocaties = $this->getLocations();
        foreach ($inloopLocaties as $locatie) {
            $this->log($locatie);
            $strategies = $this->getStrategies($locatie);
//            $this->log("Strategies used: ".get_class($strategy));

            $filter = new KlantFilter($strategies);

            $filter->huidigeStatus = Aanmelding::class; //alleen klanten met een inloopdossier mogen toegang.

            $builder = $this->klantDao->getAllQueryBuilder($filter);
            $builder
                ->andWhere('klant.id = :klant_id')
                ->setParameter('klant_id', $klant->getId());

//            $sql = $builder->getQuery()->getSQL();
//            $this->log($builder->getQuery()->getSQL());


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

    private function getLocations()
    {
        return $this->locatieDao->findAllActiveLocationsOfTypeInloop();
    }

    private function getStrategies(Locatie $locatie)
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
         *
         *
         */
        $strategies = [
            new SpecificLocationStrategy($this->locatieDao),
            new IntakelocatieStrategy($this->intake_locaties),
            new AmocStrategy($this->amoc_locaties, $this->amocVerblijfsstatus),
            new GebruikersruimteStrategy(),
            new ToegangOverigStrategy($this->intake_locaties),
        ];

        $supportedStrategies = [];

        foreach ($strategies as $strategy) {
            if ($strategy->supports($locatie)) {
                $supportedStrategies[] = $strategy;
            }

        }
        return $supportedStrategies;

        throw new \LogicException('No supported strategy found!');
    }

    private function getKlantIds(QueryBuilder $builder)
    {
        $klantIdArray = $builder->select('klant.id')->distinct(true)->getQuery()->getResult();
        return array_map(
            function ($klantId) {
                return $klantId['id'];
            }, $klantIdArray

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
