<?php

namespace GaBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManager;
use GaBundle\Entity\GaKlantIntake;
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

        /* @var $gaIntake GaKlantIntake */
        $gaIntake = $this->entityManager->getRepository(GaKlantIntake::class)->findOneBy([
            'klant' => $klant,
        ]);

        if ($gaIntake) {
            $url = $this->generator->generate('groepsactiviteiten_intakes_view', [
                'klant_id' => $klant->getId(),
            ]);
            $dienst = [
                'name' => 'GA',
                'url' => $url,
                'from' => $gaIntake->getIntakedatum() ? $gaIntake->getIntakedatum()->format('d-m-Y') : null,
                'to' => null,
                'type' => 'date',
                'value' => '',
            ];
            $event->addDienst($dienst);
        }
    }
}
