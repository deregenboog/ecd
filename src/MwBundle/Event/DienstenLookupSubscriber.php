<?php

namespace MwBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\Verslag;

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

        /* @var $verslag Verslag */
        $verslag = $this->entityManager->getRepository(Verslag::class)->findOneBy(
            ['klant' => $klant],
            ['datum' => 'asc']
        );

        if ($verslag) {
            $url = $this->generator->generate('mw_klanten_view', [
                'id' => $klant->getId(),
            ]);
            $dienst = [
                'name' => 'Mw',
                'url' => $url,
                'from' => $verslag->getDatum() ? $verslag->getDatum()->format('d-m-Y') : null,
                'to' => null,
                'type' => 'date',
                'value' => '',
            ];
            $event->addDienst($dienst);
        }
    }
}
