<?php

namespace InloopBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\RecenteRegistratie;
use InloopBundle\Entity\Registratie;
use InloopBundle\Event\Events;
use InloopBundle\Filter\RegistratieFilter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class RegistratieDao extends AbstractDao implements RegistratieDaoInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    protected $paginationOptions = [
        'defaultSortFieldName' => 'registratie.binnen',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'registratie.binnen',
            'registratie.buiten',
            'registratie.douche',
            'registratie.kleding',
            'registratie.maaltijd',
            'registratie.veegploeg',
            'registratie.activering',
            'registratie.mw',
            'klant.id',
            'klant.voornaam',
            'klant.achternaam',
            'geslacht.volledig',
            'locatie.naam',
        ],
    ];

    protected $class = Registratie::class;

    protected $alias = 'registratie';

    public function __construct(
        EntityManager $entityManager,
        PaginatorInterface $paginator,
        $itemsPerPage,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($entityManager, $paginator, $itemsPerPage);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, locatie, geslacht")
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->where("registratie.binnen >= '2017-01-01 00:00:00'")
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function findLatestByKlantAndLocatie(Klant $klant, Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.klant", 'klant', 'WITH', 'klant = :klant')
            ->innerJoin("{$this->alias}.locatie", 'locatie', 'WITH', 'locatie = :locatie')
            ->orderBy("{$this->alias}.binnen", 'DESC')
            ->setParameters([
                'klant' => $klant,
                'locatie' => $locatie,
            ])
            ->setMaxResults(1)
        ;
//        $sql = SqlExtractor::getFullSQL($builder->getQuery());


        return $builder->getQuery()->getOneOrNullResult();
    }

    public function checkCorona(Klant $klant)
    {
        $locatiesGeweest = [];
        //vind alle registraties van deze klant
        $recenteRegistraties = $klant->getRegistratiesSinds(new \DateTime('today -11 days'));
        foreach($recenteRegistraties as $registratie)
        {
            $locatiesGeweest[] = $registratie->getLocatie();
        }

        /**
         * Vind alle registraties van die locaties, waarbij klanten betrokken waren die besmet waren in de periode dat deze klant daar geweest is.
         */
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.locatie", 'locatie', 'WITH', 'locatie IN (:locaties)')
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->where("registratie.binnen >= :vanaf")
            ->andWhere("registratie.buiten <= :totEnMet OR registratie.buiten IS NULL")
            ->andWhere("klant.coronaBesmetVanaf >= :vanaf")
            ->andWhere("klant.coronaBesmetVanaf <= :totEnMet")
            ->orderBy("{$this->alias}.binnen", 'DESC')
            ->setParameters([
                'locaties' => $locatiesGeweest,
                'vanaf' => new \DateTime('today -11 days'),
                'totEnMet' => new \DateTime('today midnight'),
            ])
        ;
//        $sql = $builder->getQuery()->getSQL();

        $registratiesBesmetteKlanten = $builder->getQuery()->getResult();
        foreach($registratiesBesmetteKlanten as $besmetteRegistratie)
        {
            $besmetteKlant = $besmetteRegistratie->getKlant();
            /**
             * Check bij registraties van huidige klant of deze gematcht kunnen worden met een besmette klant.
             */
            foreach($recenteRegistraties as $registratie){

                $binnen = $registratie->getBinnen();
                $buiten = $registratie->getBuiten();
                if(is_null($buiten)) $buiten = $binnen;
                $locatie = $registratie->getLocatie();

                /**
                 * zoek een match van de besmette klant op de locatie en datum van een recente registratie van in te checken persoon
                 */
                $criteria = Criteria::create()
                    //besmet persoon moet pas naar buiten zijn gegaan toen gezond persoon binnen was
                    ->where(Criteria::expr()->gte('buiten', $binnen))
                    //en besmet persoon moet binnen zijn geweest voordat gezond persoon naar buiten ging
                    ->andWhere(Criteria::expr()->lte('binnen', $buiten))
                    //op de locatie waar ik was.
                    ->andWhere(Criteria::expr()->eq("locatie",$locatie))
                    ->orderBy(['id' => 'DESC'])
                ;
//                $sql = $criteria->getWhereExpression();
               $match = $besmetteKlant->getRegistraties()->matching($criteria);
               if((is_array($match) || $match instanceof \Countable ? count($match) : 0) > 0)
               {
                   return true; //match gevonden: op locatie en binnen tijdsvak aanwezig met besmet persoon.
               }
            }


        }
        return false;
    }
    /**
     * @param bool $type the value of either self::TYPE_DAY of self::TYPE_NIGHT
     *
     * @return Registratie[]
     */
    public function findAutoCheckoutCandidates($type)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->where("{$this->alias}.closed = false AND locatie.nachtopvang = :type")
            ->orWhere("{$this->alias}.buiten < {$this->alias}.binnen")
            ->setParameter('type', (bool) $type)
        ;

        return $builder->getQuery()->getResult();
    }

    public function create(Registratie $entity)
    {
         parent::doCreate($entity);

        return $entity;

    }

    public function update(Registratie $entity)
    {
        return parent::doUpdate($entity);
    }

    public function delete(Registratie $entity)
    {
        $this->removeFromQueues($entity);

        return parent::doDelete($entity);
    }

    public function checkout(Registratie $registratie, \DateTime $time = null)
    {
        if ($registratie->getBuiten()) {
            return false;
        }

        if (!$time) {
            $time = new \DateTime();
        }

        $this->removeFromQueues($registratie);
        $registratie->setBuiten($time);
        $this->update($registratie);

        $this->eventDispatcher->dispatch(new GenericEvent($registratie), Events::CHECKOUT);

        return true;
    }

    public function removeFromQueues(Registratie $registratie)
    {
        if ($registratie->getDouche() > 0) {
            $registratie->setDouche(0);
            $this->update($registratie);
            $this->reorderShowerQueue($registratie->getLocatie());
        }

        if ($registratie->getMw() > 0) {
            $registratie->setMw(0);
            $this->update($registratie);
            $this->reorderMwQueue($registratie->getLocatie());
        }
    }

    public function delKlantFromShowerList($registratie_id, &$registraties, &$registratie_data)
    {
        $registraties_list = [];

        foreach ($registraties as $key => $registratie) {
            if ($registratie['Registratie']['douche'] > 0) {
                $registraties_list[$registratie['Registratie']['id']] = $registratie['Registratie']['douche'];
            }
        }

        asort($registraties_list);
        $r_to_save = [];

        if ($registratie_data['Registratie']['douche'] > 0) {
            unset($registraties_list[$registratie_id]);
            $r_to_save[$registratie_data['Registratie']['id']]['id'] = $registratie_data['Registratie']['id'];
            $r_to_save[$registratie_data['Registratie']['id']]['douche'] = -1;
            $inc = 1;
            foreach ($registraties_list as $key => $value) {
                $r_to_save[$key]['id'] = $key;
                $r_to_save[$key]['douche'] = $inc;
                ++$inc;
            }
        } elseif (-1 == $registratie_data['Registratie']['douche']) {
            $r_to_save[$registratie_data['Registratie']['id']]['id'] = $registratie_data['Registratie']['id'];
            $r_to_save[$registratie_data['Registratie']['id']]['douche'] = 0;
        }
        $this->saveAll($r_to_save);
        foreach ($registraties as $r_key => $registratie_value) {
            foreach ($r_to_save as $registratie_saved) {
                if ($registratie_value['Registratie']['id'] == $registratie_saved['id']) {
                    $registraties[$r_key]['Registratie']['douche'] = $registratie_saved['douche'];
                }
            }
        }

        return $registraties;
    }

    public function checkoutKlantFromAllLocations(Klant $klant)
    {
        $registraties = $this->repository->createQueryBuilder('registratie')
            ->where('registratie.klant = :klant')
            ->andWhere('registratie.buiten IS NULL')
            ->setParameter('klant', $klant)
            ->getQuery()
            ->getResult()
        ;

        foreach ($registraties as $registratie) {
            $this->checkOut($registratie);
        }
    }

    public function findActiveByLocatie(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->leftJoin('registratie.klant', 'klant')
            ->andwhere('registratie.locatie = :locatie')
            ->andwhere('registratie.closed = false')
            ->setParameter('locatie', $locatie)
        ;

        if ($locatie->isGebruikersruimte()) {
            $builder
                ->leftJoin('klant.laatsteIntake', 'intake')
                ->orderBy('intake.magGebruiken', 'ASC')
                ->addOrderBy('registratie.created', 'DESC')
            ;
        }

        return $builder->getQuery()->getResult();
    }

    public function findActiveGebruikersruimteByLocatie(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->leftJoin('registratie.klant', 'klant')
            ->innerJoin('klant.laatsteIntake', 'intake', 'WITH', 'intake.magGebruiken = true')
            ->andwhere('registratie.locatie = :locatie')
            ->andwhere('registratie.closed = false')
            ->setParameter('locatie', $locatie)
            ->orderBy('intake.magGebruiken', 'ASC')
            ->addOrderBy('registratie.created', 'DESC')
        ;

        return $builder->getQuery()->getResult();
    }

    public function getActiveRegistraties(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->leftJoin('registratie.klant', 'klant')
            ->andwhere('registratie.locatie = :locatie')
            ->andwhere('registratie.closed = false')
            ->setParameter('locatie', $locatie)
        ;

        if ($locatie->isGebruikersruimte()) {
            $builder
                ->leftJoin('klant.laatsteIntake', 'intake')
                ->orderBy('intake.magGebruiken', 'ASC')
                ->addOrderBy('registratie.created', 'DESC')
            ;
        }

        $regularKlanten = $builder->getQuery()->getResult();

        if ($locatie->isGebruikersruimte()) {
            $gebruikers = [];
            foreach ($regularKlanten as $registratie) {
                if (!$registratie->getKlant()->getLaatsteIntake()->isMagGebruiken()) {
                    continue;
                }

                array_unshift($gebruikers, $klant);
            }
        }

        return [$regularKlanten, $gebruikers];
    }

    public function findActive($page = null, FilterInterface $filter = null)
    {
        // show all in one page
        $this->itemsPerPage = 10000;

        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, locatie, klant, schorsing, opmerking")
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin('klant.schorsingen', 'schorsing')
            ->leftJoin('klant.opmerkingen', 'opmerking')
            ->where("{$this->alias}.closed = false")
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function findHistory($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, locatie, klant, huidigeStatus, schorsing, opmerking")
            ->innerJoin(RecenteRegistratie::class, 'recent', 'WITH', 'recent.registratie = registratie')
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin('klant.huidigeStatus', 'huidigeStatus', 'WITH', 'huidigeStatus INSTANCE OF '.Aanmelding::class)
            ->leftJoin('klant.schorsingen', 'schorsing')
            ->leftJoin('klant.opmerkingen', 'opmerking')
            ->where("{$this->alias}.closed = true")
        ;

        $this->paginationOptions['defaultSortFieldName'] = 'registratie.buiten';

        return parent::doFindAll($builder, $page, $filter);
    }

    public function findShowerQueue(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->innerJoin('registratie.locatie', 'locatie')
            ->where('registratie.closed = false')
            ->andWhere('registratie.douche > 0')
            ->orderBy('registratie.douche')
            ->setParameter('locatie', $locatie)
        ;

        $filter = new RegistratieFilter($locatie);
        $filter->douche = 1;
        $filter->applyTo($builder);

        return $builder->getQuery()->getResult();
    }

    public function reorderShowerQueue(Locatie $locatie)
    {
        $queue = $this->findShowerQueue($locatie);
        foreach ($queue as $i => $registratie) {
            $registratie->setDouche(1 + $i);
        }
        $this->entityManager->flush();
    }

    public function findMwQueue(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->innerJoin('registratie.locatie', 'locatie')
            ->where('registratie.closed = false')
            ->andWhere('registratie.mw > 0')
            ->orderBy('registratie.mw')
            ->setParameter('locatie', $locatie)
        ;

        $filter = new RegistratieFilter($locatie);
        $filter->mw = 1;
        $filter->applyTo($builder);

        return $builder->getQuery()->getResult();
    }

    public function reorderMwQueue(Locatie $locatie)
    {
        $queue = $this->findMwQueue($locatie);
        foreach ($queue as $i => $registratie) {
            $registratie->setMw(1 + $i);
        }
        $this->entityManager->flush();
    }
}
