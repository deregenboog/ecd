<?php

namespace OekBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManager;
use OekBundle\Entity\Deelnemer;
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
        $deelnemer = $this->entityManager->getRepository(Deelnemer::class)
            ->findOneBy(['klant' => $klant]);

        if ($deelnemer instanceof Deelnemer) {
            $dienst = new Dienst(
                'Op eigen kracht',
                $this->generator->generate('oek_deelnemers_view', ['id' => $deelnemer->getId()])
            );

            if ($deelnemer->getAanmelding()) {
                $dienst->setVan($deelnemer->getAanmelding()->getDatum());
            }

            if ($deelnemer->getAfsluiting()) {
                $dienst->setTot($deelnemer->getAfsluiting()->getDatum());
            }

            $event->addDienst($dienst);
        }
    }
}
