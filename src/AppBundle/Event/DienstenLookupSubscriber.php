<?php

namespace AppBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Service\KlantDaoInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DienstenLookupSubscriber implements EventSubscriberInterface
{
    /**
     * @var KlantDaoInterface
     */
    private $klantDao;

    public static function getSubscribedEvents(): array
    {
        return [
            Events::DIENSTEN_LOOKUP => ['lookupKlant', 1024],
        ];
    }

    public function __construct(KlantDaoInterface $klantDao)
    {
        $this->klantDao = $klantDao;
    }

    /**
     * Store Klant object in event for subsequent subscribers to use.
     *
     * @param DienstenLookupEvent $event
     */
    public function lookupKlant(DienstenLookupEvent $event)
    {
        $klant = $this->klantDao->find($event->getKlantId());
        $event->setKlant($klant);
    }
}
