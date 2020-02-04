<?php

namespace OekBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events as DoctrineEvents;
use OekBundle\Entity\Deelnemer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
                Events::EVENT_DEELNEMER_CREATED,
                new GenericEvent($args->getEntity())
            );
        }
    }
}
