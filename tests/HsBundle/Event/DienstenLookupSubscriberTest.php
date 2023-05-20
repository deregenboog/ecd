<?php

namespace Tests\HsBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Event\DienstenLookupSubscriber;
use HsBundle\Service\DienstverlenerDaoInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DienstenLookupSubscriberTest extends TestCase
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
        $expected = new Dienst('Homeservice', '/generated-url');
        $expected->setVan(new \DateTime('2017-02-03'));
        $this->assertEquals($expected, $diensten[0]);
    }
}
