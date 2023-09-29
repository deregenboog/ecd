<?php

namespace InloopBundle\Event;

use InloopBundle\Entity\Intake;
use InloopBundle\Service\AccessUpdater;
use InloopBundle\Service\KlantDaoInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class IntakeSubscriber implements EventSubscriberInterface
{
    private KlantDaoInterface $klantDao;
    private LoggerInterface $logger;
    private Environment $twig;
    private MailerInterface $mailer;
    private $informeleZorgEmail;
    private $dagbestedingEmail;
    private $inloophuisEmail;
    private $hulpverleningEmail;

    public function __construct(
        KlantDaoInterface $klantDao,
        LoggerInterface $logger,
        Environment $twig,
        MailerInterface $mailer,
        AccessUpdater $accessUpdater,
        $informeleZorgEmail,
        $dagbestedingEmail,
        $hulpverleningEmail
    ) {
        $this->klantDao = $klantDao;
        $this->logger = $logger;
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->accessUpdater = $accessUpdater;
        $this->informeleZorgEmail = $informeleZorgEmail;
        $this->dagbestedingEmail = $dagbestedingEmail;
        $this->hulpverleningEmail = $hulpverleningEmail;
    }

    public static function getSubscribedEvents(): array
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
            $klant->setHuidigeStatus(new \InloopBundle\Entity\Aanmelding($intake->getMedewerker() ));
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

//        $content = $this->twig->render('InloopBundle:intakes:aanmelding.txt.twig', [
//            'intake' => $intake,
//        ]);

        $message = (new TemplatedEmail())
            ->addFrom(new Address($intake->getMedewerker()->getEmail(),'ECD Inloop Intake ('.$intake->getMedewerker()->getNaam().')'))
            ->subject("Verzoek (Inloop intake)")
            ->textTemplate('@Inloop\intakes\aanmelding.txt.twig')
            ->context([
                'intake'=>$intake
            ])
        ;
        foreach($addresses as $rcpt)
        {
            $message->addTo($rcpt);
        }

        try {
            $sent = $this->mailer->send($message);
        } catch (TransportException $e) {
            $sent = false;
        }

        if ($sent === null) {
            $this->logger->debug('Email intake verzonden', ['intake' => $intake->getId(), 'to' => $addresses]);
        } else {
            $this->logger->error('Email intake kon niet worden verzonden', ['intake' => $intake->getId(), 'to' => $addresses]);
        }
    }
}
