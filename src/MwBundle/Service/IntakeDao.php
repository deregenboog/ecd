<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Locatie;
use InloopBundle\Event\Events;
use InloopBundle\Service\LocatieDao;
use InloopBundle\Service\LocatieDaoInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class IntakeDao extends AbstractDao implements IntakeDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'intake.intakedatum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'intake.intakedatum',
            'klant.id',
            'klant.achternaam',
            'klant.voornaam',
            'werkgebied.naam',
            'geslacht.volledig',
            'intakelocatie.naam',
            'laatsteIntake.intakedatum',
            'eersteIntake.intakelocatie.naam'
        ],
    ];

    protected $class = Intake::class;

    protected $alias = 'intake';

    protected $wachtlijstLocaties = array();
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        EntityManager $entityManager,
        PaginatorInterface $paginator,
        $itemsPerPage,
        EventDispatcherInterface $eventDispatcher,
        LocatieDaoInterface $locatieDao

    ) {
        $wachtlijstlocaties = $locatieDao->getWachtlijstLocaties();

        $this->wachtlijstLocaties = $wachtlijstlocaties;
        parent::__construct($entityManager, $paginator, $itemsPerPage);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)

                ->addSelect("klant,eersteIntake,laatsteIntake,werkgebied")
                ->innerJoin("intake.klant","klant")
                ->innerJoin("klant.geslacht","geslacht")
                ->innerJoin("klant.eersteIntake","eersteIntake")
                ->innerJoin("eersteIntake.intakelocatie","intakelocatie")
                ->innerJoin("klant.laatsteIntake","laatsteIntake")
                ->innerJoin('klant.werkgebied','werkgebied')
                ->where("intakelocatie.naam IN (:wachtlijstLocaties)")
                ->setParameter("wachtlijstLocaties",$this->wachtlijstLocaties)
              ->groupBy("klant.laatsteIntake")
        ;

        $q = $builder->getQuery();

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * @param Intake $entity
     *
     * @return Intake
     */
    public function create(Intake $entity)
    {
        parent::doCreate($entity);

        $this->eventDispatcher->dispatch(Events::INTAKE_CREATED, new GenericEvent($entity));

        return $entity;
    }

    /**
     * @param Intake $entity
     *
     * @return Intake
     */
    public function update(Intake $entity)
    {
        parent::doUpdate($entity);

        $this->eventDispatcher->dispatch(Events::INTAKE_UPDATED, new GenericEvent($entity));

        return $entity;
    }
}
