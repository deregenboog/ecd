<?php

namespace ErOpUitBundle\Event;

use GaBundle\Entity\Intake as GaIntake;
use GaBundle\Entity\Lidmaatschap;
use GaBundle\Service\GroepDaoInterface;
use GaBundle\Service\klantdossierDaoInterface;
use GaBundle\Service\LidmaatschapDaoInterface;
use IzBundle\Entity\Intake as IzIntake;
use IzBundle\Entity\IzKlant;
use IzBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use ErOpUitBundle\Service\KlantDaoInterface;
use ErOpUitBundle\Entity\Klant;

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
        /* @var $izIntake Intake */
        $izIntake = $event->getSubject();

        // do nothing if not client
        if (!$izIntake->getIzDeelnemer() instanceof IzKlant) {
            return;
        }

        $klant = $izIntake->getIzDeelnemer()->getKlant();

        // do nothing if already exists
        $erOpUitDossier = $this->klantDao->findOneByKlant($klant);
        if ($erOpUitDossier instanceof Klant) {
            return;
        }

        $erOpUitDossier = new Klant($klant);
        $this->klantDao->create($erOpUitDossier);
    }
}
