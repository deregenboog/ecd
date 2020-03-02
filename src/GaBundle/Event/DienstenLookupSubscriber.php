<?php

namespace GaBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManagerInterface;
use GaBundle\Entity\Klantdossier;
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
        $klant = $event->getKlant();
        $dossier = $this->entityManager->getRepository(Klantdossier::class)
            ->findOneBy(['klant' => $klant]);

        if ($dossier instanceof Klantdossier) {
            $dienst = new Dienst(
                'Groepsactiviteiten',
                $this->generator->generate('ga_klantdossiers_view', ['id' => $dossier->getId()])
            );
            $dienst->setVan($dossier->getAanmelddatum());

            if ($dossier->getAfsluitdatum()) {
                $dienst->setTot($dossier->getAfsluitdatum());
            }

            $event->addDienst($dienst);
        }
    }
}
