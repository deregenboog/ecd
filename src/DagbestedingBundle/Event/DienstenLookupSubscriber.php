<?php

namespace DagbestedingBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use DagbestedingBundle\Entity\Deelnemer;
use Doctrine\ORM\EntityManager;
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
        if (!$klant instanceof Klant) {
            $klant = $this->entityManager->find(Klant::class, $event->getKlantId());
            // store in event for subsequent subscribers to use
            $event->setKlant($klant);
        }

        $deelnemer = $this->entityManager->getRepository(Deelnemer::class)
            ->findOneBy(['klant' => $klant]);

        if ($deelnemer instanceof Deelnemer) {
            if ($deelnemer->getAanmelddatum() && $deelnemer->getAfsluitdatum()) {
                $value = sprintf('Van %s tot %s', $deelnemer->getAanmelddatum()->format('d-m-Y'), $deelnemer->getAfsluitdatum()->format('d-m-Y'));
            } elseif ($deelnemer->getAanmelddatum()) {
                $value = sprintf('Sinds %s', $deelnemer->getAanmelddatum()->format('d-m-Y'));
            }

            if (count($deelnemer->getTrajecten()) > 0) {
                $value .= sprintf(' (trajectbegeleider: %s)', (string) $deelnemer->getTrajecten()[0]->getBegeleider());
            }

            $event->addDienst([
                'name' => 'Dagbesteding',
                'url' => $this->generator->generate('dagbesteding_deelnemers_view', ['id' => $deelnemer->getId()]),
                'type' => 'string',
                'value' => $value,
            ]);
        }
    }
}
