<?php

namespace InloopBundle\Event;

use InloopBundle\Entity\Intake;
use InloopBundle\Service\AccessUpdater;
use InloopBundle\Service\KlantDao;
use InloopBundle\Service\KlantDaoInterface;
use MwBundle\Entity\Aanmelding;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class IntakeSubscriber implements EventSubscriberInterface
{
    /**
     * @var KlantDao
     */
    private $klantDao;
    private $logger;
    private $templating;
    private $mailer;
    private $informeleZorgEmail;
    private $dagbestedingEmail;
    private $inloophuisEmail;
    private $hulpverleningEmail;

    public function __construct(
        KlantDaoInterface $klantDao,
        LoggerInterface $logger,
        EngineInterface $templating,
        \Swift_Mailer $mailer,
        AccessUpdater $accessUpdater,
        $informeleZorgEmail,
        $dagbestedingEmail,
        $hulpverleningEmail
    ) {
        $this->klantDao = $klantDao;
        $this->logger = $logger;
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->accessUpdater = $accessUpdater;
        $this->informeleZorgEmail = $informeleZorgEmail;
        $this->dagbestedingEmail = $dagbestedingEmail;
        $this->hulpverleningEmail = $hulpverleningEmail;
    }

    public static function getSubscribedEvents()
    {
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

        $this->checkInloopdossier($intake);
        $this->updateAccess($intake);
        $this->sendIntakeNotification($intake);
    }

    public function afterIntakeUpdated(GenericEvent $event)
    {
        $intake = $event->getSubject();
        if (!$intake instanceof Intake) {
            return;
        }

        $this->checkInloopdossier($intake);
        $this->updateAccess($intake);
    }

    public function updateAccess(Intake $intake)
    {
        $this->accessUpdater->updateForClient($intake->getKlant());
    }

    public function checkInloopDossier(Intake $intake)
    {
        if($intake->getMedewerker() == null) return;


        $klant = $intake->getKlant();
        if($klant->getHuidigeStatus() == null)
        {
            $klant->setHuidigeStatus(new \InloopBundle\Entity\Aanmelding($klant,$intake->getMedewerker() ));
        }

        $this->klantDao->update($klant);

    }
    public function sendIntakeNotification(Intake $intake)
    {
        $addresses = [];
        if ($intake->isInformeleZorg()) {
            $addresses[] = $this->informeleZorgEmail;
        }
        if ($intake->isDagbesteding()) {
            $addresses[] = $this->dagbestedingEmail;
        }
        if ($intake->isHulpverlening()) {
            $addresses[] = $this->hulpverleningEmail;
        }
        $addresses = array_unique($addresses);

        if (0 === count($addresses)) {
            return;
        }

        $content = $this->templating->render('InloopBundle:intakes:aanmelding.txt.twig', [
            'intake' => $intake,
        ]);

        /** @var \Swift_Mime_Message $message */
        $message = $this->mailer->createMessage()
            ->setFrom('noreply@deregenboog.org')
            ->setTo($addresses)
            ->setSubject('Verzoek')
            ->setBody($content, 'text/plain')
        ;

        try {
            $sent = $this->mailer->send($message);
        } catch (\Exception $e) {
            $sent = false;
        }

        if ($sent) {
            $this->logger->debug('Email intake verzonden', ['intake' => $intake->getId(), 'to' => $addresses]);
        } else {
            $this->logger->error('Email intake kon niet worden verzonden', ['intake' => $intake->getId(), 'to' => $addresses]);
        }
    }
}
