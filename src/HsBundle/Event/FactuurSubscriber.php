<?php

namespace HsBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use HsBundle\Entity\FactuurSubjectInterface;
use HsBundle\Service\FactuurFactoryInterface;

class FactuurSubscriber implements EventSubscriber
{
    private $factuurFactory;

    public function __construct(FactuurFactoryInterface $factuurFactory)
    {
        $this->factuurFactory = $factuurFactory;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createOrUpdateFacuur($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->createOrUpdateFacuur($args);
    }

    private function createOrUpdateFacuur(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof FactuurSubjectInterface) {
            $factuur = $this->factuurFactory->create($entity->getKlus()->getKlant());
            $entity->setFactuur($factuur);
            $args->getEntityManager()->persist($factuur);
        }
    }
}
