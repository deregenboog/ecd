<?php

namespace InloopBundle\Event;

use InloopBundle\Entity\DossierStatus;
use InloopBundle\Service\AccessUpdater;
use InloopBundle\Service\KlantDao;
use InloopBundle\Service\KlantDaoInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class DossierSubscriber implements EventSubscriberInterface
{
    /**
     * @var KlantDao
     */
    private $klantDao;

    /**
     * @var AccessUpdater
     */
    private $accessUpdater;

    public function __construct(
        KlantDaoInterface $klantDao,
        AccessUpdater $accessUpdater
    ) {
        $this->klantDao = $klantDao;
        $this->accessUpdater = $accessUpdater;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::DOSSIER_CHANGED => ['afterDossierUpdated'],
        ];
    }

    public function afterDossierUpdated(GenericEvent $event)
    {
        $dossier = $event->getSubject();
        if (!$dossier instanceof DossierStatus) {
            return;
        }

        $this->accessUpdater->updateForClient($dossier->getKlant());
    }
}
