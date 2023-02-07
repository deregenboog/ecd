<?php

namespace ErOpUitBundle\Event;

use ErOpUitBundle\Entity\Klant;
use ErOpUitBundle\Service\KlantDaoInterface;
use OekBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class OekDeelnemerSubscriber implements EventSubscriberInterface
{
    /**
     * @var KlantDaoInterface
     */
    private $klantDao;

    public static function getSubscribedEvents()
    {
        return [
            Events::EVENT_DEELNEMER_CREATED => ['addToEropuit'],
        ];
    }

    public function __construct(KlantDaoInterface $klantDao)
    {
        $this->klantDao = $klantDao;
    }

    public function addToEropuit(GenericEvent $event)
    {
        // do nothing if not client
        $klant = $event->getSubject()->getKlant();
        if (!$klant) {
            return;
        }

        // create if not already exists
        $erOpUitDossier = $this->klantDao->findOneByKlant($klant);
        if (!$erOpUitDossier) {
            $this->klantDao->create(new Klant($klant));
        }
    }
}
