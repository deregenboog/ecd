<?php

namespace OekraineBundle\Event;

use OekraineBundle\Entity\Locatie;
use OekraineBundle\Service\AccessUpdater;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class LocatieSubscriber implements EventSubscriberInterface
{
    /**
     * @var AccessUpdater
     */
    private $accessUpdater;

    public function __construct(
        AccessUpdater $accessUpdater
    ) {
        $this->accessUpdater = $accessUpdater;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::LOCATIE_CHANGED => ['afterLocatieUpdated'],
        ];
    }

    public function afterLocatieUpdated(GenericEvent $event)
    {
        return; // deze staat nog uit want de accessUpdater doet niet wat ik wil.

        // $entity = $event->getSubject();
        // if (!$entity instanceof Locatie) {
        //     return;
        // }

        // $this->accessUpdater->updateForLocation($entity);
    }
}
