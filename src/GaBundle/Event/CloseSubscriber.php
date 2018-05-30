<?php

namespace GaBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use GaBundle\Entity\GaKlantIntake;
use Symfony\Component\EventDispatcher\GenericEvent;
use GaBundle\Entity\GaGroepErOpUit;
use GaBundle\Entity\GaGroep;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Entity\Persoon;

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
            $erOpUit = $this->entityManager->getRepository(GaGroep::class)->createQueryBuilder('groep')
                ->innerJoin('groep.gaKlantLeden', 'lid', 'WITH', 'lid = :klant')
                ->where('groep.id = :id')
                ->setParameters([
                    'id' => 19,
                    'klant' => $persoon,
                ])
                ->getQuery()
                ->getOneOrNullResult()
            ;
        } elseif ($persoon instanceof Vrijwilliger) {
            $erOpUit = $this->entityManager->getRepository(GaGroep::class)->createQueryBuilder('groep')
                ->innerJoin('groep.gaVrijwilligerLeden', 'lid', 'WITH', 'lid = :vrijwilliger')
                ->where('groep.id = :id')
                ->setParameters([
                    'id' => 19,
                    'vrijwilliger' => $persoon,
                ])
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

        return (bool) $erOpUit;
    }

    private function generateUrl(Persoon $persoon)
    {
        if ($persoon instanceof Klant) {
            return $this->generator->generate('groepsactiviteiten_klanten_groepen_view', [
                'klant_id' => $persoon->getId(),
            ]);
        } elseif ($persoon instanceof Vrijwilliger) {
            return $this->generator->generate('groepsactiviteiten_vrijwilligers_groepen_view', [
                'vrijwilliger_id' => $persoon->getId(),
            ]);
        }
    }
}
