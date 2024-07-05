<?php

namespace OekraineBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManagerInterface;
use OekraineBundle\Entity\Aanmelding;
use OekraineBundle\Entity\Afsluiting;
use OekraineBundle\Entity\Bezoeker;
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

        $bezoeker = $this->entityManager->getRepository(Bezoeker::class)
        ->findOneBy(['appKlant' => $klant]);

        // && !$klant->getDisabled() && $klant->getHuidigeStatus()->isAangemeld() ?
        if ($bezoeker instanceof Bezoeker) {
            $dienst = new Dienst(
                'Oekraine',
                $this->generator->generate('oekraine_bezoekers_view', ['id' => $bezoeker->getId()])
            );

            if ($bezoeker->getDossierStatus() instanceof Aanmelding) {
                $dienst->setVan($bezoeker->getDossierStatus()->getDatum());
            }

            if ($bezoeker->getDossierStatus() instanceof Afsluiting) {
                $dienst->setTot($bezoeker->getDossierStatus()->getDatum());
            }

            $event->addDienst($dienst);
        }
    }
}
