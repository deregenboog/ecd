<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Entity\AccessFields;
use InloopBundle\Entity\Intake;
use InloopBundle\Event\Events;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class AccessFieldsDao extends AbstractDao implements AccessFieldsDaoInterface
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

    protected $class = AccessFields::class;

    protected $alias = 'accessFields';

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

    /**
     * @return AccessFields
     */
    public function create(AccessFields $entity)
    {
        parent::doCreate($entity);

        $this->eventDispatcher->dispatch(new GenericEvent($entity), Events::ACCESS_FIELDS_CREATED);

        return $entity;
    }

    /**
     * @return AccessFields
     */
    public function update(AccessFields $entity)
    {
        parent::doUpdate($entity);

        $this->eventDispatcher->dispatch(new GenericEvent($entity), Events::ACCESS_FIELDS_UPDATED);

        return $entity;
    }
}
