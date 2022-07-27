<?php

namespace OekraineBundle\Event;

use Doctrine\ORM\EntityManager;
use OekraineBundle\Entity\RecenteRegistratie;
use OekraineBundle\Entity\Registratie;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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

        // recente registratie(s) voor deze klant/locatie/datum verwijderen
        $registraties = $this->entityManager->getRepository(RecenteRegistratie::class)->createQueryBuilder('registratie')
            ->where('registratie.bezoeker = :bezoeker AND registratie.locatie = :locatie AND DATE(registratie.buiten) = :today')
            ->setParameters([
                'bezoeker' => $registratie->getBezoeker(),
                'locatie' => $registratie->getLocatie(),
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
