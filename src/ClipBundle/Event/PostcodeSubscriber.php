<?php

namespace ClipBundle\Event;

use AppBundle\Entity\Postcode;
use ClipBundle\Entity\Client;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class PostcodeSubscriber implements EventSubscriber
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
        $this->setWerkgebiedAndGgwGebied($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->setWerkgebiedAndGgwGebied($args);
    }

    public function setWerkgebiedAndGgwGebied(LifecycleEventArgs $args)
    {
        $client = $args->getObject();

        if ($client instanceof Client) {
            $entityManager = $args->getEntityManager();
            if ($client->getPostcode()) {
                /** @var $postcode Postcode */
                $postcode = $entityManager->find(Postcode::class, $client->getPostcode());
                $client->setWerkgebied($postcode ? $postcode->getStadsdeel() : null);
                $client->setPostcodegebied($postcode ? $postcode->getPostcodegebied() : null);
                $entityManager->flush($client);
            }
        }
    }
}
