<?php

namespace DagbestedingBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\Events;
use AppBundle\Event\DienstenLookupEvent;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Klant;
use DagbestedingBundle\Entity\Deelnemer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DienstenLookupSubscriber implements EventSubscriberInterface
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
            Events::DIENSTEN_LOOKUP => ['provideDienstenInfo'],
        ];
    }

    public function __construct(EntityManager $entityManager, UrlGeneratorInterface $generator)
    {
        $this->entityManager = $entityManager;
        $this->generator = $generator;
    }

    public function provideDienstenInfo(DienstenLookupEvent $event)
    {
        $klant = $event->getKlant();
        if (!$klant instanceof Klant) {
            $klant = $this->entityManager->find(Klant::class, $event->getKlantId());
            // store in event for subsequent subscribers to use
            $event->setKlant($klant);
        }

        $deelnemer = $this->entityManager->getRepository(Deelnemer::class)
            ->findOneBy(['klant' => $klant]);

        if ($deelnemer instanceof Deelnemer) {
            $event->addDienst([
                'name' => 'Dagbesteding',
                'url' => $this->generator->generate('dagbesteding_deelnemers_view', ['id' => $deelnemer->getId()]),
                'from' => $deelnemer->getAanmelddatum() ? $deelnemer->getAanmelddatum()->format('Y-m-d') : null,
                'to' => $deelnemer->getAfsluitdatum() ? $deelnemer->getAfsluitdatum()->format('Y-m-d') : null,
                'type' => 'date',
                'value' => '',
            ]);
        }
    }
}
