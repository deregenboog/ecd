<?php

namespace InloopBundle\Event;

use InloopBundle\Entity\DossierStatus;
use InloopBundle\Entity\Intake;
use InloopBundle\Service\AccessUpdater;
use InloopBundle\Service\KlantDao;
use InloopBundle\Service\KlantDaoInterface;
use MwBundle\Entity\Aanmelding;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class DossierSubscriber implements EventSubscriberInterface
{
    /**
     * @var KlantDao
     */
    private $klantDao;


    public function __construct(
        KlantDaoInterface $klantDao,
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
