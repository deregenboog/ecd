<?php

namespace AppBundle\Event;

use AppBundle\Service\VrijwilligerDaoInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DienstenVrijwilligerLookupSubscriber implements EventSubscriberInterface
{
    /**
     * @var VrijwilligerDaoInterface
     */
    private $vrijwilligerDao;

    public static function getSubscribedEvents(): array
    {
        return [
            Events::DIENSTEN_VRIJWILLIGER_LOOKUP => ['lookupVrijwilliger', 1024],
        ];
    }

    public function __construct(VrijwilligerDaoInterface $vrijwilligerDao)
    {
        $this->vrijwilligerDao = $vrijwilligerDao;
    }

    /**
     * Store Vrijwilliger object in event for subsequent subscribers to use.
     */
    public function lookupVrijwilliger(DienstenVrijwilligerLookupEvent $event)
    {
        $vrijwilliger = $this->vrijwilligerDao->find($event->getVrijwilligerId());
        $event->setVrijwilliger($vrijwilliger);
    }
}
