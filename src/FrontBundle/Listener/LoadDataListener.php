<?php

namespace FrontBundle\Listener;

use AncaRebeca\FullCalendarBundle\Model\FullCalendarEvent;
use AppBundle\Entity\Event as MyCustomEvent;

class LoadDataListener
{
    /**
     * @param Event $calendarEvent
     *
     * @return FullCalendarEvent[]
     */
    public function loadData(Event $calendarEvent)
    {
        $startDate = $calendarEvent->getStart();
        $endDate = $calendarEvent->getEnd();
        $filters = $calendarEvent->getFilters();

        //You may want do a custom query to populate the events
        $ev1 = new MyCustomEvent();
        $ev1->setName("hello");
        $ev1->setDate(new \DateTime());
        $calendarEvent->addEvent($ev1);
        //$calendarEvent->addEvent(new MyCustomEvent('Event Title 1', new \DateTime()));
        //$calendarEvent->addEvent(new MyCustomEvent('Event Title 2', new \DateTime()));
    }
}