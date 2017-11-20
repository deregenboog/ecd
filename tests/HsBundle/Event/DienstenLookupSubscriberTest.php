<?php

namespace Tests\HsBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\Events;
use AppBundle\Event\DienstenLookupEvent;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Klant;
use OekBundle\Entity\OekKlant;
use HsBundle\Event\DienstenLookupSubscriber;
use HsBundle\Service\KlantDaoInterface;
use HsBundle\Service\DienstverlenerDaoInterface;
use HsBundle\Entity\Dienstverlener;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DienstenLookupSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSubscribedEvents()
    {
        $expected = [
            Events::DIENSTEN_LOOKUP => ['provideDienstenInfo'],
        ];
        $this->assertEquals($expected, DienstenLookupSubscriber::getSubscribedEvents());
    }

    public function testProvideDienstenInfo()
    {
        $event = new DienstenLookupEvent(1, []);
        $event->setKlant(new Klant());

        $dienstverlener = new Dienstverlener();
        $dienstverlener->setInschrijving(new \DateTime('2017-02-03'));

        $dienstverlenerDao = $this->createMock(DienstverlenerDaoInterface::class);
        $dienstverlenerDao->method('findOneByKlant')->with($event->getKlant())->willReturn($dienstverlener);

        $generator = $this->createMock(UrlGeneratorInterface::class);
        $generator->expects($this->once())->method('generate')
            ->with('hs_dienstverleners_view', ['id' => $dienstverlener->getId()])
            ->willReturn('/generated-url');

        $subscriber = new DienstenLookupSubscriber($dienstverlenerDao, $generator);
        $subscriber->provideDienstenInfo($event);

        $diensten = $event->getDiensten();
        $this->assertCount(1, $diensten);
        $expected = [
            'name' => 'Homeservice',
            'url' => '/generated-url',
            'from' => '2017-02-03',
            'to' => null,
            'type' => 'date',
            'value' => '',
        ];
        $this->assertEquals($expected, $diensten[0]);
    }
}
