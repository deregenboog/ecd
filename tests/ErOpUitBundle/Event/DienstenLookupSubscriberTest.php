<?php

namespace Tests\ErOpUitBundle\Event;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Entity\Medewerker;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use ErOpUitBundle\Entity\Klant;
use ErOpUitBundle\Event\DienstenLookupSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DienstenLookupSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents()
    {
        $this->assertEquals([
            Events::DIENSTEN_LOOKUP => ['provideDienstenInfo'],
        ], DienstenLookupSubscriber::getSubscribedEvents());
    }

    public function testProvideDienstenInfo()
    {
        $appKlant = new AppKlant();
        $medewerker = (new Medewerker())->setVoornaam('Piet')->setAchternaam('Pietersen');
        $inschrijfdatum = new \DateTime('2024-01-01');
        $uitschrijfdatum = new \DateTime('2024-02-27');
        $klant = (new Klant())->setKlant($appKlant);
        $klant->setInschrijfdatum($inschrijfdatum)->setUitschrijfdatum($uitschrijfdatum);

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['klant' => $appKlant])
            ->willReturn($klant);
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('getRepository')->with(Klant::class)
            ->willReturn($repository);

        $generator = $this->createMock(UrlGeneratorInterface::class);
        $generator->expects($this->once())->method('generate')->willReturn('http://www.example.com/');

        $event = new DienstenLookupEvent($klant->getId());
        $event->setKlant($appKlant);

        $subscriber = new DienstenLookupSubscriber($em, $generator);
        $subscriber->provideDienstenInfo($event);

        $diensten = $event->getDiensten();
        $this->assertCount(1, $diensten);
        $this->assertEquals('ErOpUit-kalender', $diensten[0]->getNaam());
        $this->assertEquals('http://www.example.com/', $diensten[0]->getUrl());
        $this->assertNull($diensten[0]->getOmschrijving());
        $this->assertEquals($inschrijfdatum, $diensten[0]->getVan());
        $this->assertEquals($uitschrijfdatum, $diensten[0]->getTot());
        $this->assertNull($diensten[0]->getTitelMedewerker());
        $this->assertNull($diensten[0]->getMedewerker());
    }
}
