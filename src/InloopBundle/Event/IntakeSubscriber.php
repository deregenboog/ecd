<?php

namespace InloopBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use InloopBundle\Entity\Intake;
use InloopBundle\Service\AccessUpdater;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManager;

class IntakeSubscriber implements EventSubscriber
{
    private $logger;
    private $templating;
    private $mailer;
    private $informeleZorgEmail;
    private $dagbestedingEmail;
    private $inloophuisEmail;
    private $hulpverleningEmail;

    public function __construct(
        LoggerInterface $logger,
        EngineInterface $templating,
        \Swift_Mailer $mailer,
        PaginatorInterface $paginator,
        $informeleZorgEmail,
        $dagbestedingEmail,
        $inloophuisEmail,
        $hulpverleningEmail
    ) {
        $this->logger = $logger;
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->paginator = $paginator;
        $this->informeleZorgEmail = $informeleZorgEmail;
        $this->dagbestedingEmail = $dagbestedingEmail;
        $this->inloophuisEmail = $inloophuisEmail;
        $this->hulpverleningEmail = $hulpverleningEmail;
    }

    public function getSubscribedEvents()
    {
        return [
            'postPersist',
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->updateAccess($args);
        $this->sendIntakeNotification($args);
    }

    public function updateAccess(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Intake) {
            return;
        }

        $accessUpdater = new AccessUpdater($args->getEntityManager(), $this->paginator, 25000);
        $accessUpdater->updateForClient($entity->getKlant());
    }

    public function sendIntakeNotification(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Intake) {
            return;
        }

        $addresses = [];
        if ($entity->isInformeleZorg()) {
            $addresses[] = $this->informeleZorgEmail;
        }
        if ($entity->isDagbesteding()) {
            $addresses[] = $this->dagbestedingEmail;
        }
        if ($entity->isInloophuis()) {
            $addresses[] = $this->inloophuisEmail;
        }
        if ($entity->isHulpverlening()) {
            $addresses[] = $this->hulpverleningEmail;
        }
        $addresses = array_unique($addresses);

        if (0 === count($addresses)) {
            return;
        }

        $content = $this->templating->render('InloopBundle:intakes:aanmelding.txt.twig', [
            'intake' => $entity,
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
            $this->logger->debug('Email intake verzonden', ['intake' => $entity->getId(), 'to' => $addresses]);
        } else {
            $this->logger->error('Email intake kon niet worden verzonden', ['intake' => $entity->getId(), 'to' => $addresses]);
        }
    }
}
