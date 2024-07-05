<?php

namespace OekraineBundle\Service;

use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Event\Events;
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
            'locatie.wachtlijst',
        ],
    ];

    protected $class = Locatie::class;

    protected $alias = 'locatie';

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

    public function create(Locatie $locatie)
    {
        $this->doCreate($locatie);
        $this->eventDispatcher->dispatch(new GenericEvent($locatie), Events::LOCATIE_CHANGED);
    }

    public function update(Locatie $locatie)
    {
        $this->doUpdate($locatie);
        $this->eventDispatcher->dispatch(new GenericEvent($locatie), Events::LOCATIE_CHANGED);
    }

    public function delete(Locatie $locatie)
    {
        $this->doDelete($locatie);
        $this->eventDispatcher->dispatch(new GenericEvent($locatie), Events::LOCATIE_CHANGED);
    }

    public function getWachtlijstLocaties()
    {
        $builder = $this->entityManager->createQueryBuilder('locatie');
        $builder->select('locatie.naam')
            ->from(Locatie::class, 'locatie')
            ->where('locatie.wachtlijst > 0');
        $wachtlijstlocaties = $builder->getQuery()->getResult();

        return $wachtlijstlocaties;
    }
}
