<?php

namespace HsBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Betaling;
use Doctrine\ORM\EntityManager;

class KlantUpdater implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->handleEvent($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->handleEvent($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->handleEvent($args);
    }

    private function handleEvent(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Factuur) {
            $this->updateSaldo($entity->getKlant(), $args->getEntityManager());
        }

        if ($entity instanceof Betaling) {
            $this->updateSaldo($entity->getFactuur()->getKlant(), $args->getEntityManager());
        }
    }

    private function updateSaldo(Klant $klant, EntityManager $em)
    {
        $klant->setSaldo($klant->getBetaald() - $klant->getGefactureerd());

        $em->flush($klant);
    }
}
