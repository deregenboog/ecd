<?php

namespace GaBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Persoon;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManager;
use GaBundle\Entity\Groep;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ErOpUitBundle;

class CloseSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public static function getSubscribedEvents()
    {
        return [
            Events::BEFORE_CLOSE => ['provideBeforeCloseMessage'],
        ];
    }

    public function __construct(EntityManager $entityManager, UrlGeneratorInterface $generator)
    {
        $this->entityManager = $entityManager;
        $this->generator = $generator;
    }

    public function provideBeforeCloseMessage(GenericEvent $event)
    {
        if (!$this->supportsClass($event->getSubject())) {
            return;
        }

        if ($this->hasErOpUitSubscription($event->getSubject())) {
            $message = sprintf(
                'Let op, deze persoon is lid van de groep "ErOpUit Kalender".
                Wellicht moet dat lidmaatschap opgezegd worden.
                <a href="%s">Klik hier</a> om dat te doen (hiervoor zijn ECD-toegangsrechten voor Groepsactiviteiten nodig).',
                $this->generateUrl($event->getSubject())
            );

            $event->setArgument('messages', array_merge($event->getArgument('messages'), [$message]));
        }
    }

    private function supportsClass($subject)
    {
        if ($subject instanceof Klant || $subject instanceof Vrijwilliger) {
            return true;
        }

        return false;
    }

    private function hasErOpUitSubscription(Persoon $persoon)
    {
        if ($persoon instanceof Klant) {
            $erOpUit = $this->entityManager->getRepository(ErOpUitBundle\Entity\Klant::class)
                ->findOneBy(['klant' => $persoon]);
        } elseif ($persoon instanceof Vrijwilliger) {
            $erOpUit = $this->entityManager->getRepository(ErOpUitBundle\Entity\Vrijwilliger::class)
                ->findOneBy(['vrijwilliger' => $persoon]);
        }

        return $erOpUit && !$erOpUit->isUitgeschreven();
    }

    private function generateUrl(Persoon $persoon)
    {
        if ($persoon instanceof Klant) {
            return $this->generator->generate('eropuit_klanten_view', [
                'id' => $persoon->getId(),
            ]);
        } elseif ($persoon instanceof Vrijwilliger) {
            return $this->generator->generate('eropuit_vrijwilligers_view', [
                'id' => $persoon->getId(),
            ]);
        }
    }
}
