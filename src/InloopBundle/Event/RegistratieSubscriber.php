<?php

namespace InloopBundle\Event;

use InloopBundle\Entity\Intake;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use InloopBundle\Entity\Registratie;
use InloopBundle\Entity\RecenteRegistratie;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;

class RegistratieSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public static function getSubscribedEvents()
    {
        return [
            Events::CHECKOUT => ['updateRecenteRegistraties'],
        ];
    }

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateRecenteRegistraties(GenericEvent $event)
    {
        $registratie = $event->getSubject();

        if (!$registratie instanceof Registratie) {
            return;
        }

        // huidige recente registratie verwijderen
        $registraties = $this->entityManager->getRepository(RecenteRegistratie::class)->createQueryBuilder('registratie')
            ->where('registratie.klant = :klant AND registratie.locatie = :locatie AND DATE(registratie.buiten) = :today')
            ->setParameters([
                'klant' => $registratie->getKlant(),
                'locatie'  => $registratie->getLocatie(),
                'today' => new \DateTime('today'),
            ])->getQuery()->getResult();
        foreach ($registraties as $current) {
            $this->entityManager->remove($current);
        }
        $this->entityManager->flush();

        // nieuewe recente registratie toevoegen
        $recent = new RecenteRegistratie($registratie);
        $this->entityManager->persist($recent);
        $this->entityManager->flush();
    }
}
