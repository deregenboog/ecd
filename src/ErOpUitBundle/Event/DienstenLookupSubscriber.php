<?php

namespace ErOpUitBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManagerInterface;
use ErOpUitBundle\Entity\Klant;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    public static function getSubscribedEvents()
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
        $klant = $this->entityManager->getRepository(Klant::class)->findOneBy([
            'klant' => $event->getKlant(),
        ]);

        if ($klant instanceof Klant) {
            $dienst = new Dienst(
                'ErOpUit-kalender',
                $this->generator->generate('eropuit_klanten_view', ['id' => $klant->getId()])
            );

            if ($klant->getInschrijfdatum()) {
                $dienst->setVan($klant->getInschrijfdatum());
            }

            if ($klant->getUitschrijfdatum()) {
                $dienst->setTot($klant->getUitschrijfdatum());
            }

            $event->addDienst($dienst);
        }
    }
}
