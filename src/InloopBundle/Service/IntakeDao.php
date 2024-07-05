<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Entity\Intake;
use InloopBundle\Event\Events;
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
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        $itemsPerPage,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($entityManager, $paginator, $itemsPerPage);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->addSelect('klant, intakelocatie, geslacht')
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin("{$this->alias}.intakelocatie", 'intakelocatie')
            ->leftJoin('klant.geslacht', 'geslacht')
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function getFirstFiveIntakesForTesting()
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->addSelect('klant, intakelocatie, geslacht')
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin("{$this->alias}.intakelocatie", 'intakelocatie')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->where("klant.roepnaam LIKE 'Toegang%' ")
            ->orderBy('klant.id', 'asc')
//            ->setMaxResults(5)
        ;
        $sql = $builder->getQuery()->getSQL();
        //        return parent::doFindAll($builder);
        $a = $builder->getQuery()->getArrayResult();

        return $builder;

        //        return $this->repository->findOneBy(['username' => $username]);
    }

    /**
     * @return Intake
     */
    public function create(Intake $entity)
    {
        parent::doCreate($entity);

        $this->eventDispatcher->dispatch(new GenericEvent($entity), Events::INTAKE_CREATED);

        return $entity;
    }

    /**
     * @return Intake
     */
    public function update(Intake $entity)
    {
        parent::doUpdate($entity);

        $this->eventDispatcher->dispatch(new GenericEvent($entity), Events::INTAKE_UPDATED);

        return $entity;
    }
}
