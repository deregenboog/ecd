<?php

namespace TwBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TwBundle\Entity\Deelnemer;
use TwBundle\Entity\Klant;
use TwBundle\Entity\Verhuurder;

class DienstenLookupSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public static function getSubscribedEvents(): array
    {
        return [
            Events::DIENSTEN_LOOKUP => ['provideDienstenInfo'],
        ];
    }

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $generator)
    {
        $this->entityManager = $entityManager;
        $this->generator = $generator;
    }

    public function provideDienstenInfo(DienstenLookupEvent $event)
    {
        $url = null;
        $klant = $event->getKlant();
        $deelnemer = $this->entityManager->getRepository(Deelnemer::class);
        $deelnemer = $deelnemer->findOneBy(['appKlant' => $klant]);

        if ($deelnemer instanceof Deelnemer) {
            $type = "";
            if ($deelnemer instanceof Klant) {
                $type = "Klant";
                $url = $this->generator->generate('tw_klanten_view', ['id' => $deelnemer->getId()]);
            } elseif ($deelnemer instanceof Verhuurder) {
                $type = "Verhuurder";
                $url = $this->generator->generate('tw_verhuurders_view', ['id' => $deelnemer->getId()]);
            }

            $dienst = new Dienst('Tijdelijk wonen', $url);

            if ($deelnemer->getAanmelddatum()) {
                $dienst->setVan($deelnemer->getAanmelddatum());
                $dienst->setOmschrijving(sprintf("(%s)",$type));
            }

            if ($deelnemer->getAfsluitdatum()) {
                $dienst->setTot($deelnemer->getAfsluitdatum());
            }
            $dienst->setEntity($deelnemer);

            $event->addDienst($dienst);
        }
    }
}
