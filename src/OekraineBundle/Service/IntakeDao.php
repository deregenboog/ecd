<?php

namespace OekraineBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManager;
use OekraineBundle\Entity\Intake;
use OekraineBundle\Event\Events;
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
            'bezoeker.id',
            'appKlant.id',
            'appKlant.voornaam',
            'appKlant.achternaam',
            'geslacht.volledig',
            'intakelocatie.naam',
        ],
    ];

    protected $class = Intake::class;

    protected $alias = 'intake';

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

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
            ->addSelect('bezoeker,appKlant, woonlocatie, intakelocatie, geslacht')
            ->innerJoin("{$this->alias}.bezoeker", 'bezoeker')
            ->innerJoin("bezoeker.appKlant", 'appKlant')
            ->leftJoin("{$this->alias}.woonlocatie", 'woonlocatie')
            ->leftJoin("{$this->alias}.intakelocatie", 'intakelocatie')
            ->leftJoin('appKlant.geslacht', 'geslacht')
        ;

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

        $this->eventDispatcher->dispatch(new GenericEvent($entity), Events::INTAKE_CREATED);

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

        $this->eventDispatcher->dispatch(new GenericEvent($entity), Events::INTAKE_UPDATED);

        return $entity;
    }
}
