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
        // && !$klant->getDisabled() && $klant->getHuidigeStatus()->isAangemeld() ?
        if ($klant->getLaatsteIntake()) {
            $toegang = $this->entityManager->getRepository(Toegang::class)->findBy(['klant' => $klant]);
            if (count($toegang) > 0) {
                $inloophuizen = [];
                $gebruikersruimtes = [];
                foreach ($toegang as $t) {
                    if ($t->getLocatie()->isGebruikersruimte()) {
                        $gebruikersruimtes[] = (string) $t->getLocatie();
                    } else if(strpos($t->getLocatie()->getNaam(), 'WKR') === false && strpos($t->getLocatie()->getNaam(), 'winteropvang') === false) {
                        $inloophuizen[] = (string) $t->getLocatie();
                    }
                    else
                    {
                        $winter[] = (string) $t->getLocatie();
                    }

                }
                if (count($inloophuizen) > 0) {
                    sort($inloophuizen);
                    $event->addDienst(new Dienst(
                        'Inloophuizen',
                        $this->generator->generate('inloop_klanten_view', ['id' => $klant->getId()]),
                        sprintf('%s',$klant->getHuidigeStatus()),
                        implode('<br/>', $inloophuizen)
                    ));
                }
                if (count($gebruikersruimtes) > 0) {
                    sort($gebruikersruimtes);
                    $event->addDienst(new Dienst(
                        'Gebruikersruimte',
                        $this->generator->generate('inloop_klanten_view', ['id' => $klant->getId()]),
                        implode(', ', $gebruikersruimtes)
                    ));
                }

                /// #FARHAD: winteropvang added here via Jan, its the reason why the Diensten are diffrent with the Master branch
                if (count($winter) > 0) {
                    sort($winter);
                    $event->addDienst(new Dienst(
                        'Winteropvang',
                        $this->generator->generate('inloop_klanten_view', ['id' => $klant->getId()]),
                        sprintf('%s',$klant->getHuidigeStatus()),
                        implode('<br/>', $winter)
                    ));
                }
            }
        }
    }
}
