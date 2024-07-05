<?php

namespace HsBundle\Event;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use HsBundle\Entity\Betaling;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;

class KlantUpdater implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(PostPersistEventArgs $args)
    {
        $this->handleEvent($args);
    }

    public function postUpdate(PostUpdateEventArgs $args)
    {
        $this->handleEvent($args);
    }

    public function postRemove(PostRemoveEventArgs $args)
    {
        $this->handleEvent($args);
    }

    private function handleEvent(EventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Factuur) {
            $this->updateSaldo($entity->getKlant(), $args->getEntityManager());
        }

        if ($entity instanceof Betaling) {
            $this->updateSaldo($entity->getFactuur()->getKlant(), $args->getEntityManager());
        }
    }

    private function updateSaldo(Klant $klant, EntityManagerInterface $em)
    {
        $klant->setSaldo($klant->getBetaald() - $klant->getGefactureerd());

        $em->flush();
    }
}
