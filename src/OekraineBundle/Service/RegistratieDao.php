<?php

namespace OekraineBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use OekraineBundle\Entity\Aanmelding;
use OekraineBundle\Entity\Bezoeker;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Entity\RecenteRegistratie;
use OekraineBundle\Entity\Registratie;
use OekraineBundle\Event\Events;
use OekraineBundle\Filter\RegistratieFilter;
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

        return $builder->getQuery()->getOneOrNullResult();
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

        $this->eventDispatcher->dispatch( new GenericEvent($registratie), Events::CHECKOUT);

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


    public function checkoutBezoekerFromAllLocations(Bezoeker $bezoeker)
    {
        $registraties = $this->repository->createQueryBuilder('registratie')
            ->where('registratie.bezoeker = :bezoeker')
            ->andWhere('registratie.buiten IS NULL')
            ->setParameter('bezoeker', $bezoeker)
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
            ->leftJoin('registratie.bezoeker', 'bezoeker')
            ->andwhere('registratie.locatie = :locatie')
            ->andwhere('registratie.closed = false')
            ->setParameter('locatie', $locatie)
        ;


        return $builder->getQuery()->getResult();
    }

    public function getActiveRegistraties(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->leftJoin('registratie.bezoeker', 'bezoeker')
            ->andwhere('registratie.locatie = :locatie')
            ->andwhere('registratie.closed = false')
            ->setParameter('locatie', $locatie)
        ;


        $regularKlanten = $builder->getQuery()->getResult();


        return [$regularKlanten, []];
    }

    public function findActive($page = null, FilterInterface $filter = null)
    {
        // show all in one page
        $this->itemsPerPage = 10000;

        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, locatie, bezoeker, appKlant")
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->innerJoin("{$this->alias}.bezoeker", 'bezoeker')
            ->innerJoin("bezoeker.appKlant", 'appKlant')
//            ->leftJoin('klant.schorsingen', 'schorsing')
//            ->leftJoin('klant.opmerkingen', 'opmerking')
            ->where("{$this->alias}.closed = false")
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function findHistory($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, locatie, bezoeker, appKlant")
//            ->innerJoin(RecenteRegistratie::class, 'recent', 'WITH', 'recent.registratie = registratie')
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->innerJoin("{$this->alias}.bezoeker", 'bezoeker')
            ->innerJoin("bezoeker.appKlant","appKlant")
//            ->leftJoin('bezoeker.dossierStatus', 'huidigeStatus', 'WITH', 'huidigeStatus INSTANCE OF '.Aanmelding::class)
//            ->where("{$this->alias}.closed = true")
        ;


        $this->paginationOptions['defaultSortFieldName'] = 'registratie.buiten';
        return parent::doFindAll($builder, $page, $filter);
    }
}
