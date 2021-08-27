<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Locatie;
use InloopBundle\Event\Events;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class LocatieDao extends AbstractDao implements LocatieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'locatie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'locatie.id',
            'locatie.naam',
            'locatie.actief',
        ],
    ];

    protected $class = Locatie::class;

    protected $alias = 'locatie';

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

    public function create(Locatie $locatie)
    {
        $this->doCreate($locatie);
        $this->eventDispatcher->dispatch(Events::LOCATIE_CHANGED, new GenericEvent($locatie));
    }

    public function update(Locatie $locatie)
    {
        $this->doUpdate($locatie);
        $this->eventDispatcher->dispatch(Events::LOCATIE_CHANGED, new GenericEvent($locatie));
    }

    public function delete(Locatie $locatie)
    {
        $this->doDelete($locatie);
        $this->eventDispatcher->dispatch(Events::LOCATIE_CHANGED, new GenericEvent($locatie));
    }

    public function getWachtlijstLocaties()
    {
        $builder = $this->entityManager->createQueryBuilder("locatie");
        $builder->select("locatie.naam")
            ->from(Locatie::class,"locatie")
            ->where("locatie.wachtlijst = 1");
        $wachtlijstlocaties = $builder->getQuery()->getResult();
        return $wachtlijstlocaties;
    }
}
