<?php

namespace IzBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManagerInterface;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\Verslag;
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
        $klant = $event->getKlant();
        /** @var IzKlant $izKlant */
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

            /** @var Hulpvraag $hulpvraag */
            $hulpvraag = $this->entityManager->getRepository(Hulpvraag::class)->findOneBy(
                ['izKlant' => $izKlant],
                ['created' => 'desc']
            );
            if ($hulpvraag) {
                $medewerker = $hulpvraag->getMedewerker();
                if ($medewerker) {
                    $dienst->setTitelMedewerker('coördinator')->setMedewerker($medewerker);
                }
            }

            if (!$dienst->getMedewerker()) {
                /** @var Verslag $verslag */
                $verslag = $this->entityManager->getRepository(Verslag::class)->findOneBy(
                    ['izDeelnemer' => $izKlant],
                    ['created' => 'desc']
                );
                if ($verslag) {
                    $medewerker = $verslag->getMedewerker();
                    if ($medewerker) {
                        $dienst->setTitelMedewerker('contactpersoon')->setMedewerker($medewerker);
                    }
                }
            }

            $event->addDienst($dienst);
        }
    }
}
