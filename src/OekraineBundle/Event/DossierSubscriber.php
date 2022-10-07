<?php

namespace OekraineBundle\Event;

use OekraineBundle\Entity\DossierStatus;
use OekraineBundle\Entity\Intake;
use OekraineBundle\Service\AccessUpdater;
use OekraineBundle\Service\BezoekerDao;
use OekraineBundle\Service\KlantDaoInterface;
use MwBundle\Entity\Aanmelding;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class DossierSubscriber implements EventSubscriberInterface
{
    /**
     * @var BezoekerDao
     */
    private $klantDao;


    public function __construct(
        BezoekerDao $klantDao,
        AccessUpdater $accessUpdater
    ) {
        $this->klantDao = $klantDao;
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

        $this->accessUpdater->updateForClient($dossier->getKlant());

    }
}
