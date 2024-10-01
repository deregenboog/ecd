<?php

namespace VillaBundle\Event;

use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use VillaBundle\Service\OvernachtingDaoInterface;

class CalendarSubscriber implements EventSubscriberInterface
{

    protected OvernachtingDaoInterface $dao;
    public function __construct(OvernachtingDaoInterface $overnachtingDao)
    {
        $this->dao = $overnachtingDao;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();



      if($filters['calendar-id'] == 'villa-slapers' && null !== $filters['entity-id'] && is_numeric($filters['entity-id']))
      {
          $events = $this->dao->findOvernachtingenByEntityIdForDateRange($filters['entity-id'],$start, $end);
          foreach ($events as $overnachting) {
              $event =  new Event(
                      'Overnachting',
                      $overnachting->getDatum(),
              );
              $event->setAllDay(true);

              $event->addOption('id',$overnachting->getId());
              $calendar->addEvent($event);
              }

      }
    }
}