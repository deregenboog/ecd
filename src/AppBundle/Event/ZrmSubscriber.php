<?php

namespace AppBundle\Event;

use AppBundle\Entity\Zrm;
use AppBundle\Model\ZrmInterface;
use AppBundle\Model\ZrmsInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class ZrmSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->setMetadata($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->setMetadata($args);
    }

    public function setMetadata(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $zrms = [];

        if ($entity instanceof ZrmInterface) {
            /* @var $zrm Zrm */
            $zrms = [$entity->getZrm()];
        } elseif ($entity instanceof ZrmsInterface) {
            $zrms = $entity->getZrms();
        }

        if (!$zrms) {
            return;
        }

        /* @var $zrm Zrm */
        foreach (array_filter($zrms) as $zrm) {
            if (false === ($zrm->getModel() && $zrm->getForeignKey())) {
                $zrm->setModel(get_class($entity))->setForeignKey($entity->getId());
                $args->getEntityManager()->flush($zrm);
            }
        }
    }
}
