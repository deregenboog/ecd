<?php

namespace OekBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\Events;
use AppBundle\Event\DienstenLookupEvent;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Klant;
use OekBundle\Entity\OekKlant;

class DienstenLookupSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public static function getSubscribedEvents()
    {
        return [
            Events::DIENSTEN_LOOKUP => ['provideDienstenInfo'],
        ];
    }

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function provideDienstenInfo(DienstenLookupEvent $event)
    {
        $klant = $event->getKlant();
        if (!$klant instanceof Klant) {
            $klant = $this->entityManager->find(Klant::class, $event->getKlantId());
            // store in event for subsequent subscribers to use
            $event->setKlant($klant);
        }

        $oekKlant = $this->entityManager->getRepository(OekKlant::class)
            ->findOneBy(['klant' => $klant]);

        if ($oekKlant instanceof OekKlant) {
            $event->addDienst([
                'name' => 'Op eigen kracht',
                'url' => [
                    'controller' => 'oek_klanten',
                    'action' => 'view',
                    $oekKlant->getId(),
                ],
                'from' => $oekKlant->getOekAanmelding() ? $oekKlant->getOekAanmelding()->getDatum()->format('Y-m-d') : null,
                'to' => $oekKlant->getOekAfsluiting() ? $oekKlant->getOekAfsluiting()->getDatum()->format('Y-m-d') : null,
                'type' => 'date',
                'value' => '',
            ]);
        }
    }
}
