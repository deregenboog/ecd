<?php

namespace GaBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManager;
use GaBundle\Entity\KlantIntake;
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
        $klant = $event->getKlant();
        $intake = $this->entityManager->getRepository(KlantIntake::class)
            ->findOneBy(['klant' => $klant]);

        if ($intake instanceof KlantIntake) {
            $event->addDienst([
                'name' => 'Groepsactiviteiten',
                'url' => $this->generator->generate('ga_klantintakes_view', ['id' => $intake->getId()]),
                'from' => $intake->getIntakedatum() ? $intake->getIntakedatum()->format('d-m-Y') : null,
                'to' => $intake->getAfsluitdatum() ? $intake->getAfsluitdatum()->format('d-m-Y') : null,
                'type' => 'date',
                'value' => '',
            ]);
        }
    }
}
