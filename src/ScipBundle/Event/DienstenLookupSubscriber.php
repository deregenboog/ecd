<?php

namespace ScipBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManagerInterface;
use ScipBundle\Entity\Deelnemer;
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

        /* @var $deelnemer Deelnemer */
        $deelnemer = $this->entityManager->getRepository(Deelnemer::class)
            ->findOneBy(['klant' => $klant]);

        if ($deelnemer) {
            $dienst = new Dienst(
                'SCIP',
                $this->generator->generate('scip_deelnemers_view', ['id' => $deelnemer->getId()])
            );

            $projecten = [];
            foreach ($deelnemer->getProjecten() as $project) {
                $projecten[] = (string) $project;
            }
            $dienst->setOmschrijving(implode($projecten, ', '));

            $event->addDienst($dienst);
        }
    }
}
