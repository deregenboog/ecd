<?php

namespace MwBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManager;
use MwBundle\Entity\Verslag;
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

//        /* @var $verslag Verslag */
//        $verslag = $this->entityManager->getRepository(Verslag::class)->findOneBy(
//            ['klant' => $klant],
//            ['datum' => 'asc']
//        );

        if ($klant->getHuidigeMwStatus()) {
            $dienst = new Dienst(
                'Maatschappeljk werk',
                $this->generator->generate('mw_klanten_view', ['id' => $klant->getId()])
            );
            $dienst->setOmschrijving($klant->getHuidigeMwStatus());

//            $dienst->setVan($klant->getHuidigeMwStatus())

            $event->addDienst($dienst);
        }
    }
}
