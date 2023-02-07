<?php

namespace OekraineBundle\Event;

use OekraineBundle\Entity\Intake;
use OekraineBundle\Service\AccessUpdater;
use OekraineBundle\Service\BezoekerDao;
use OekraineBundle\Service\KlantDaoInterface;
use MwBundle\Entity\Aanmelding;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class IntakeSubscriber implements EventSubscriberInterface
{
    /**
     * @var BezoekerDao
     */
    private $klantDao;
    private $logger;
    private $templating;
    private $mailer;
    private $informeleZorgEmail;
    private $dagbestedingEmail;
    private $inloophuisEmail;
    private $hulpverleningEmail;

    public static function getSubscribedEvents()
    {
        return[];
        return [
            Events::INTAKE_CREATED => ['afterIntakeCreated'],
            Events::INTAKE_UPDATED => ['afterIntakeUpdated'],
        ];
    }

    public function afterIntakeCreated(GenericEvent $event)
    {
        $intake = $event->getSubject();
        if (!$intake instanceof Intake) {
            return;
        }
    }

    public function afterIntakeUpdated(GenericEvent $event)
    {
        $intake = $event->getSubject();
        if (!$intake instanceof Intake) {
            return;
        }
    }
}
