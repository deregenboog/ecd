<?php

namespace IzBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManager;
use IzBundle\Entity\IzKlant;
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
        /* @var $izKlant IzKlant */
        $izKlant = $this->entityManager->getRepository(IzKlant::class)
            ->findOneBy(['klant' => $klant]);

        if ($izKlant) {
            $url = $this->generator->generate('iz_klanten_view', [
                'id' => $izKlant->getId(),
            ]);
            if ($izKlant->getDatumAanmelding() && count($izKlant->getHulpvragen()) > 0) {
                $laatsteHulpvraag = $izKlant->getHulpvragen()[0];
                $value = sprintf('sinds %s (coördinator: %s)',
                    $izKlant->getDatumAanmelding()->format('d-m-Y'),
                    (string) $laatsteHulpvraag->getMedewerker()
                );
                $dienst = [
                    'name' => 'IZ',
                    'url' => $url,
                    'type' => 'string',
                    'value' => $value,
                ];
            } else {
                $dienst = [
                    'name' => 'IZ',
                    'url' => $url,
                    'from' => $izKlant->getDatumAanmelding() ? $izKlant->getDatumAanmelding()->format('Y-m-d') : null,
                    'to' => $izKlant->getAfsluitDatum() ? $izKlant->getAfsluitDatum()->format('Y-m-d') : null,
                    'type' => 'date',
                    'value' => '',
                ];
            }
            $event->addDienst($dienst);
        }
    }
}
