<?php

namespace HsBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\FactuurSubjectInterface;
use HsBundle\Entity\Registratie;

class FactuurSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
        ];
    }

    public function preRemove(PreRemoveEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof FactuurSubjectInterface) {
            $factuur = $entity->getFactuur();
            if ($entity instanceof Declaratie) {
                $factuur->removeDeclaratie($entity);
            } elseif ($entity instanceof Registratie) {
                $factuur->removeRegistratie($entity);
            }
            if ($factuur->isEmpty()) {
                $entityManager->remove($factuur);
            }
        }
    }
}
