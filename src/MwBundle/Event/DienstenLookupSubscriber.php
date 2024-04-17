<?php

namespace MwBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManagerInterface;
use MwBundle\Entity\Verslag;
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

        if ($klant->getHuidigeMwStatus()) {
            $dienst = new Dienst(
                'Maatschappeljk werk',
                $this->generator->generate('mw_klanten_view', ['id' => $klant->getId()])
            );
            $dienst->setOmschrijving($klant->getHuidigeMwStatus());

            /** @var Verslag $verslag */
            $verslag = $this->entityManager->getRepository(Verslag::class)->findOneBy(
                ['klant' => $klant],
                ['datum' => 'desc']
            );
            if ($verslag && $verslag->getMedewerker()) {
                $dienst->setTitelMedewerker('contactpersoon')
                    ->setMedewerker($verslag->getMedewerker());
            }

            $event->addDienst($dienst);
        }
    }
}
