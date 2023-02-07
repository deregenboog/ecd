<?php

namespace ErOpUitBundle\Event;

use ErOpUitBundle\Entity\Klant;
use ErOpUitBundle\Service\KlantDaoInterface;
use IzBundle\Entity\IzKlant;
use IzBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class IzIntakeSubscriber implements EventSubscriberInterface
{
    /**
     * @var KlantDaoInterface
     */
    private $klantDao;

    public static function getSubscribedEvents()
    {
        return [
            Events::EVENT_INTAKE_CREATED => ['addToEropuit'],
        ];
    }

    public function __construct(KlantDaoInterface $klantDao)
    {
        $this->klantDao = $klantDao;
    }

    public function addToEropuit(GenericEvent $event)
    {
        // do nothing if not client
        $izDeelnemer = $event->getSubject()->getIzDeelnemer();
        if (!$izDeelnemer instanceof IzKlant) {
            return;
        }
        $klant = $izDeelnemer->getKlant();
        if (!$klant) {
            return;
        }

        // create if not already exists
        $erOpUitDossier = $this->klantDao->findOneByKlant($klant);
        if (!$erOpUitDossier) {
            $this->klantDao->createForKlant($klant);
        }
    }
}
