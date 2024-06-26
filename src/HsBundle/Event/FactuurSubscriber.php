<?php

namespace HsBundle\Event;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use HsBundle\Entity\Creditfactuur;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\FactuurSubjectInterface;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Registratie;
use HsBundle\Exception\InvoiceLockedException;
use Doctrine\ORM\Event\LifecycleEventArgs;


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
