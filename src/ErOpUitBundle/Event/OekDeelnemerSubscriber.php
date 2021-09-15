<?php

namespace ErOpUitBundle\Event;

use ErOpUitBundle\Entity\Klant;
use ErOpUitBundle\Service\KlantDaoInterface;
use OekBundle\Event\Events;
use OekBundle\Entity\Deelnemer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class OekDeelnemerSubscriber extends ScipDeelnemerSubscriber implements EventSubscriberInterface
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
        /* @var $deelnemer Deelnemer */
        $deelnemer = $event->getSubject();

        // do nothing if not client
        if (!$deelnemer->getAppKlant() instanceof \AppBundle\Entity\Klant) {
            return;
        }

        $klant = $deelnemer->getAppKlant();

        // do nothing if already exists
        $erOpUitDossier = $this->klantDao->findOneByKlant($klant);
        if ($erOpUitDossier instanceof Klant) {
            return;
        }

        $erOpUitDossier = new Klant($klant);
        $this->klantDao->create($erOpUitDossier);
    }
}
