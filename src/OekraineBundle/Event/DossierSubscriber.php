<?php

namespace OekraineBundle\Event;

use OekraineBundle\Entity\DossierStatus;
use OekraineBundle\Service\AccessUpdater;
use OekraineBundle\Service\BezoekerDao;
use OekraineBundle\Service\BezoekerDaoInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class DossierSubscriber implements EventSubscriberInterface
{
    /**
     * @var BezoekerDao
     */
    private $bezoekerDao;

    /**
     * @var AccessUpdater
     */
    private $accessUpdater;

    public function __construct(
        BezoekerDaoInterface $bezoekerDao,
        AccessUpdater $accessUpdater
    ) {
        $this->bezoekerDao = $bezoekerDao;
        $this->accessUpdater = $accessUpdater;
    }

    public static function getSubscribedEvents()
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

        $this->accessUpdater->updateForClient($dossier->getBezoeker());
    }
}
