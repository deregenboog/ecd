<?php

namespace ErOpUitBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManager;
use ErOpUitBundle\Entity\Klant;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
        $klant = $this->entityManager->getRepository(Klant::class)->findOneBy([
            'klant' => $event->getKlant(),
        ]);

        if ($klant instanceof Klant) {
            $event->addDienst([
                'name' => 'ErOpUit-kalender',
                'url' => $this->generator->generate('eropuit_klanten_view', ['id' => $klant->getId()]),
                'from' => $klant->getInschrijfdatum() ? $klant->getInschrijfdatum()->format('d-m-Y') : null,
                'to' => $klant->getUitschrijfdatum() ? $klant->getUitschrijfdatum()->format('d-m-Y') : null,
                'type' => 'date',
                'value' => '',
            ]);
        }
    }
}
