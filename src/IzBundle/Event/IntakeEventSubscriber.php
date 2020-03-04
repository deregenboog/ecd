<?php

namespace IzBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events as DoctrineEvents;
use IzBundle\Entity\Intake;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class IntakeEventSubscriber implements EventSubscriber
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getSubscribedEvents()
    {
        return [
            DoctrineEvents::postPersist,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Intake) {
            $this->eventDispatcher->dispatch(
                new GenericEvent($args->getEntity()),
                Events::EVENT_INTAKE_CREATED
            );
        }
    }
}
