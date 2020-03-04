<?php

namespace ScipBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events as DoctrineEvents;
use ScipBundle\Entity\Deelnemer;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class DeelnemerEventSubscriber implements EventSubscriber
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
        $i = $args->getEntity();
        if ($args->getEntity() instanceof Deelnemer) {
            $this->eventDispatcher->dispatch(
                new GenericEvent($args->getEntity()),
                Events::EVENT_DEELNEMER_CREATED
            );
        }
    }
}
