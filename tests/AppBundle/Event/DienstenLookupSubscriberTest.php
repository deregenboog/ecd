<?php

declare(strict_types=1);

namespace Tests\AppBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\DienstenLookupSubscriber;
use AppBundle\Event\Events;
use AppBundle\Service\KlantDaoInterface;
use PHPUnit\Framework\TestCase;

class DienstenLookupSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            [Events::DIENSTEN_LOOKUP => ['lookupKlant', 1024]],
            DienstenLookupSubscriber::getSubscribedEvents()
        );
    }

    public function testLookupKlant()
    {
        $klantId = 123;
        $klant = new Klant();

        $klantDao = $this->getMockForAbstractClass(KlantDaoInterface::class);
        $klantDao->expects($this->once())
            ->method('find')
            ->with($klantId)
            ->willReturn($klant);

        $event = new DienstenLookupEvent($klantId);

        $subscriber = new DienstenLookupSubscriber($klantDao);
        $subscriber->lookupKlant($event);

        $this->assertSame($klant, $event->getKlant());
    }
}
