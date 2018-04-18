<?php

namespace IzBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use IzBundle\Entity\Evaluatie;
use IzBundle\Entity\Koppeling;

class KoppelingSubscriber implements EventSubscriber
{
    private $config = [];

    public function __construct($config = [])
    {
        $this->config = $config;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $koppeling = $args->getObject();

        if ($koppeling instanceof Koppeling) {
            foreach ($this->config as $config) {
                $datum = clone $koppeling->getStartdatum();
                $datum->modify($config['date_modification']);
                $koppeling->addEvaluatie(new Evaluatie($config['name'], $datum));
            }
        }
    }
}
