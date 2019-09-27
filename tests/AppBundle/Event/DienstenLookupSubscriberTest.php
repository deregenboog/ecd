<?php

namespace AppBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Service\KlantDaoInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DienstenLookupSubscriberTest extends TestCase
{
    /**
     * @var KlantDaoInterface
     */
    private $klantDao;

    /**
     * @var DienstenLookupSubscriber
     */
    private $listener;

    protected function setUp()
    {
        $this->klantDao = $this->getMockForAbstractClass(KlantDaoInterface::class);
        $this->listener = new DienstenLookupSubscriber($this->klantDao);
    }

    protected function tearDown()
    {
        $this->listener = null;
    }

    public function testIsAnEventSubscriber()
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, $this->listener);
    }

    public function testRegisteredEvent()
    {
        $this->assertEquals(
            [Events::DIENSTEN_LOOKUP => ['lookupKlant', 1024]],
            $this->listener::getSubscribedEvents()
        );
    }

    public function testLookupKlant()
    {
        $klantId = 123;
        $klant = new Klant();

        $this->klantDao->expects($this->once())
            ->method('find')
            ->with($klantId)
            ->will($this->returnValue($klant));

        $event = new DienstenLookupEvent($klantId);
        $this->listener->lookupKlant($event);

        $this->assertSame($klant, $event->getKlant());
    }
}
