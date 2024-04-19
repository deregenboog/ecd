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
            //&& !$klant->getDisabled() && $klant->getHuidigeStatus()->isAangemeld() ?
        if ($klant->getLaatsteIntake()) {
            $toegang = $this->entityManager->getRepository(Toegang::class)->findBy(['klant' => $klant]);
            if (count($toegang) > 0) {
                $gebruikersruimtes = [];
                $nInloophuizen = 0;
                foreach ($toegang as $t) {
                    if ($t->getLocatie()->isGebruikersruimte()) {
                        $gebruikersruimtes[] = (string) $t->getLocatie();
                    } else {
                        $nInloophuizen++;
                    }
                }
                if ($nInloophuizen > 0) {
                    $dienst = new Dienst(
                        'Inloophuizen',
                        $this->generator->generate('inloop_klanten_view', ['id' => $klant->getId()]),
                        'open dossier'
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
