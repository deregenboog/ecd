<?php

namespace AppBundle\Event;

use AppBundle\Entity\Zrm;
use AppBundle\Model\ZrmInterface;
use AppBundle\Model\ZrmsInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use PHPUnit\Framework\TestCase;

class ZrmSubscriberTest extends TestCase
{
    /**
     * @var ZrmSubscriber
     */
    private $listener;

    protected function setUp()
    {
        $this->listener = new ZrmSubscriber();
    }

    protected function tearDown()
    {
        $this->listener = null;
    }

    public function testIsAnEventSubscriber()
    {
        $this->assertInstanceOf(EventSubscriber::class, $this->listener);
    }

    public function testRegisteredEvent()
    {
        $this->assertEquals(
            [
                Events::postPersist,
                Events::postUpdate,
            ],
            $this->listener->getSubscribedEvents()
        );
    }

    public function testPersistEntity()
    {
        $objectManager = $this->getMockForAbstractClass(ObjectManager::class);
        $entity = $this->getMockForAbstractClass(ZrmInterface::class);
        $args = new LifecycleEventArgs($entity, $objectManager);
        $zrm = Zrm::create();

        $entity->expects($this->once())
            ->method('getZrm')
            ->will($this->returnValue($zrm));

        $entity->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(123));

        $objectManager->expects($this->once())
            ->method('flush')
            ->with($this->equalTo($zrm));

        $this->listener->postPersist($args);

        $this->assertEquals(get_class($entity), $zrm->getModel());
        $this->assertEquals(123, $zrm->getForeignKey());
    }

    public function testUpdateEntity()
    {
        $objectManager = $this->getMockForAbstractClass(ObjectManager::class);
        $entity = $this->getMockForAbstractClass(ZrmsInterface::class);
        $args = new LifecycleEventArgs($entity, $objectManager);
        $zrm = Zrm::create();

        $entity->expects($this->once())
            ->method('getZrms')
            ->will($this->returnValue([$zrm]));

        $entity->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(123));

        $objectManager->expects($this->once())
            ->method('flush')
            ->with($this->equalTo($zrm));

        $this->listener->postUpdate($args);

        $this->assertEquals(get_class($entity), $zrm->getModel());
        $this->assertEquals(123, $zrm->getForeignKey());
    }

    public function testUpdateEntityWithMetadataAlreadySet()
    {
        $objectManager = $this->getMockForAbstractClass(ObjectManager::class);
        $entity = $this->getMockForAbstractClass(ZrmsInterface::class);
        $args = new LifecycleEventArgs($entity, $objectManager);
        $zrm = Zrm::create()->setModel(get_class($entity))->setForeignKey(123);

        $entity->expects($this->once())
            ->method('getZrms')
            ->will($this->returnValue([$zrm]));

        $entity->expects($this->never())->method('getId');

        $objectManager->expects($this->never())->method('flush');

        $this->listener->postUpdate($args);

        $this->assertEquals(get_class($entity), $zrm->getModel());
        $this->assertEquals(123, $zrm->getForeignKey());
    }
}
