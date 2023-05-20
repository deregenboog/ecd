<?php

namespace IzBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
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

    public static function getSubscribedEvents(): array
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
            $dienst = new Dienst(
                'Informele Zorg',
                $this->generator->generate('iz_klanten_view', ['id' => $izKlant->getId()])
            );

            if ($izKlant->getDatumAanmelding()) {
                $dienst->setVan($izKlant->getDatumAanmelding());
            }

            if ($izKlant->getAfsluitDatum()) {
                $dienst->setTot($izKlant->getAfsluitDatum());
            }

            if ((is_array($izKlant->getHulpvragen()) || $izKlant->getHulpvragen() instanceof \Countable ? count($izKlant->getHulpvragen()) : 0) > 0) {
                $laatsteHulpvraag = $izKlant->getHulpvragen()[0];
                if ($laatsteHulpvraag->getMedewerker()) {
                    $dienst
                        ->setTitelMedewerker('coördinator')
                        ->setMedewerker($laatsteHulpvraag->getMedewerker())
                    ;
                }
            }

            $event->addDienst($dienst);
        }
    }
}
