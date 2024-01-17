<?php

namespace OekraineBundle\Event;

use Doctrine\ORM\EntityManagerInterface;
use OekraineBundle\Entity\RecenteRegistratie;
use OekraineBundle\Entity\Registratie;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class RegistratieSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public static function getSubscribedEvents(): array
    {
        return [
            Events::CHECKOUT => ['updateRecenteRegistraties'],
        ];
    }

    public function __construct(EntityManagerInterface $entityManager)
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
