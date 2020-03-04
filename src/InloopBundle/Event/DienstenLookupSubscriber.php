<?php

namespace InloopBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Entity\Toegang;
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

        if ($klant->getLaatsteIntake()) {
            $toegang = $this->entityManager->getRepository(Toegang::class)->findBy(['klant' => $klant]);
            if (count($toegang) > 0) {
                $inloophuizen = [];
                $gebruikersruimtes = [];
                foreach ($toegang as $t) {
                    if ($t->getLocatie()->isGebruikersruimte()) {
                        $gebruikersruimtes[] = (string) $t->getLocatie();
                    } else {
                        $inloophuizen[] = (string) $t->getLocatie();
                    }
                }
                if (count($inloophuizen) > 0) {
                    sort($inloophuizen);
                    $dienst = new Dienst(
                        'Inloophuizen',
                        $this->generator->generate('inloop_klanten_view', ['id' => $klant->getId()]),
                        implode(', ', $inloophuizen)
                    );

                    $event->addDienst($dienst);
                }
                if (count($gebruikersruimtes) > 0) {
                    sort($gebruikersruimtes);
                    $dienst = new Dienst(
                        'Gebr. ruimte',
                        $this->generator->generate('inloop_klanten_view', ['id' => $klant->getId()]),
                        implode(', ', $gebruikersruimtes)
                    );
                    $event->addDienst($dienst);
                }
            }
        }
    }
}
