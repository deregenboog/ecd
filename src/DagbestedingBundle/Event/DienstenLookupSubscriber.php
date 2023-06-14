<?php

namespace DagbestedingBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use DagbestedingBundle\Entity\Deelnemer;
use Doctrine\ORM\EntityManager;
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
        $deelnemer = $this->entityManager->getRepository(Deelnemer::class)
            ->findOneBy(['klant' => $klant]);

        if ($deelnemer instanceof Deelnemer) {
            $dienst = new Dienst(
                'Dagbesteding',
                $this->generator->generate('dagbesteding_deelnemers_view', ['id' => $deelnemer->getId()])
            );

            if ($deelnemer->getAanmelddatum()) {
                $dienst->setVan($deelnemer->getAanmelddatum());
            }

            if ($deelnemer->getAfsluitdatum()) {
                $dienst->setTot($deelnemer->getAfsluitdatum());
            }

            if ((is_array($deelnemer->getTrajecten()) || $deelnemer->getTrajecten() instanceof \Countable ? count($deelnemer->getTrajecten()) : 0) > 0) {
                $dienst
                    ->setTitelMedewerker('Trajectcoach')
                    ->setNaamMedewerker((string) $deelnemer->getTrajecten()[0]->getTrajectcoach())
                ;
            }

            $event->addDienst($dienst);
        }
    }
}
