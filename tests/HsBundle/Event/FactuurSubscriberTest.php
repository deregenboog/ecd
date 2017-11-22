<?php

namespace Tests\HsBundle\Event;

use HsBundle\Entity\Dienstverlener;
use HsBundle\Event\FactuurSubscriber;
use HsBundle\Service\FactuurFactoryInterface;
use Doctrine\ORM\Events;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Registratie;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ObjectManager;
use HsBundle\Entity\FactuurSubjectInterface;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Vrijwilliger;
use HsBundle\Entity\Factuur;

class FactuurSubscriberTest extends \PHPUnit_Framework_TestCase
{
    private $subscriber;

    private $factuurFactory;

    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            [Events::prePersist, Events::preUpdate],
            $this->createSubsriber()->getSubscribedEvents()
        );
    }

    public function dataProvider()
    {
        $klant = new Klant();

        $klus = new Klus();
        $klant->addKlus($klus);

        $dienstverlener = $this->createMock(Dienstverlener::class);
        $klus->addDienstverlener($dienstverlener);

        $vrijwilliger = $this->createMock(Vrijwilliger::class);
        $klus->addVrijwilliger($vrijwilliger);

        $registratieDienstverlener = new Registratie();
        $registratieDienstverlener->setKlus($klus)->setArbeider($dienstverlener);

        $registratieVrijwilliger = new Registratie();
        $registratieVrijwilliger->setKlus($klus)->setArbeider($vrijwilliger);

        $declaratie = new Declaratie();
        $declaratie->setKlus($klus);

        return [
            [$klant, $registratieDienstverlener],
            [$klant, $registratieVrijwilliger],
            [$klant, $declaratie],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testPersistingFactuurSubjectInerfaceResultsInCreatingFactuur(
        Klant $klant,
        FactuurSubjectInterface $factuurSubject
    ) {
        $subscriber = $this->createSubsriber();
        $this->factuurFactory->expects($this->exactly(2))->method('create')
            ->with($this->equalTo($klant))
            ->willReturn(new Factuur($klant, '12345', 'Homeservice-factuur'))
        ;

        $objectManager = $this->getMockForAbstractClass(ObjectManager::class);
        $event = new LifecycleEventArgs($factuurSubject, $objectManager);

        $subscriber->prePersist($event);
        $subscriber->preUpdate($event);
    }

    private function createSubsriber()
    {
        $this->factuurFactory = $this->getMockForAbstractClass(FactuurFactoryInterface::class);
        $this->subscriber = new FactuurSubscriber($this->factuurFactory);

        return $this->subscriber;
    }
}
