<?php

namespace AppBundle\Event;

use AppBundle\Entity\Zrm;
use AppBundle\Model\ZrmInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ZrmSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'postPersist',
            'postUpdate',
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

        if ($entity instanceof ZrmInterface) {
            $zrms = $entity->getZrms();
            if ($zrms) {
                /** @var $zrm Zrm */
                foreach ($zrms as $zrm) {
                    if (false === ($zrm->getModel() && $zrm->getForeignKey())) {
                        $zrm->setModel(get_class($entity))->setForeignKey($entity->getId());
                        $args->getEntityManager()->flush($zrm);
                    }
                }
            }
        }
    }
}
