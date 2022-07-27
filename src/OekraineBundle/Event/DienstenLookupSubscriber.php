<?php

namespace OekraineBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManager;
use OekBundle\Entity\Deelnemer;
use OekraineBundle\Entity\Bezoeker;
use OekraineBundle\Entity\DossierStatus;
use OekraineBundle\Entity\Toegang;
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

        $bezoeker = $this->entityManager->getRepository(Bezoeker::class)
        ->findOneBy(['appKlant' => $klant]);

            //&& !$klant->getDisabled() && $klant->getHuidigeStatus()->isAangemeld() ?
        if ($bezoeker instanceof Bezoeker ) {
            $dienst = new Dienst(
                'Oekraine',
                $this->generator->generate('oekraine_bezoekers_view', ['id' => $bezoeker->getId()])
            );

            if ($bezoeker->getAanmelding()) {
                $dienst->setVan($bezoeker->getAanmelding()->getDatum());
            }

            if ($bezoeker->getAfsluiting()) {
                $dienst->setTot($bezoeker->getAfsluiting()->getDatum());
            }

            $event->addDienst($dienst);
        }
    }
}
