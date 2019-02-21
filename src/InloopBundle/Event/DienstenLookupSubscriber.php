<?php

namespace InloopBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Toegang;
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

        if ($klant->getLaatsteIntake()) {
            $toegang = $this->entityManager->getRepository(Toegang::class)->findBy(['klant' => $klant]);
            if (count($toegang) > 0) {
                $locaties = [];
                foreach ($toegang as $t) {
                    $locaties[] = (string) $t->getLocatie();
                }
                $dienst = [
                    'name' => 'Inloophuizen',
                    'url' => null,
                    'from' => null,
                    'to' => null,
                    'type' => 'string',
                    'value' => implode(', ', $locaties),
                ];
                $event->addDienst($dienst);
            }
        }

        if ($klant->getLaatsteIntake() && $klant->getLaatsteIntake()->getGebruikersruimte()) {
            $dienst = [
                'name' => 'Gebr. ruimte',
                'url' => null,
                'from' => null,
                'to' => null,
                'type' => 'string',
                'value' => $klant->getLaatsteIntake()->getGebruikersruimte(),
            ];
            $event->addDienst($dienst);
        }
    }
}
