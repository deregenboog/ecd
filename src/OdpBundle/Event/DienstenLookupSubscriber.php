<?php

namespace OdpBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManager;
use OdpBundle\Entity\Deelnemer;
use OdpBundle\Entity\Huurder;
use OdpBundle\Entity\Verhuurder;
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
            if ($deelnemer instanceof Huurder) {
                $url = $this->generator->generate('odp_huurders_view', ['id' => $deelnemer->getId()]);
            } elseif ($deelnemer instanceof Verhuurder) {
                $url = $this->generator->generate('odp_verhuurders_view', ['id' => $deelnemer->getId()]);
            }
            $event->addDienst([
                'name' => 'Onder de Pannen',
                'url' => $url,
                'from' => $deelnemer->getAanmelddatum() ? $deelnemer->getAanmelddatum()->format('Y-m-d') : null,
                'to' => $deelnemer->getAfsluitdatum() ? $deelnemer->getAfsluitdatum()->format('Y-m-d') : null,
                'type' => 'date',
                'value' => '',
            ]);
        }
    }
}
