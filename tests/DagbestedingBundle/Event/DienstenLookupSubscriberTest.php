<?php

namespace Tests\Dagbesteding\Event;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Entity\Trajectcoach;
use DagbestedingBundle\Event\DienstenLookupSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
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
        $klant = new Klant();
        $medewerker = (new Medewerker())->setVoornaam('Piet')->setAchternaam('Pietersen');
        $aanmeldDatum = new \DateTime('2024-01-01');
        $afsluitDatum = new \DateTime('2024-02-27');
        $deelnemer = (new Deelnemer())->setKlant($klant);
        $deelnemer->setAanmeldDatum($aanmeldDatum)->setAfsluitDatum($afsluitDatum)
            ->addTraject((new Traject())->setTrajectcoach((new Trajectcoach())->setMedewerker($medewerker)));

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['klant' => $klant])
            ->willReturn($deelnemer);
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('getRepository')->with(Deelnemer::class)
            ->willReturn($repository);

        $generator = $this->createMock(UrlGeneratorInterface::class);
        $generator->expects($this->once())->method('generate')->willReturn('http://www.example.com/');

        $event = new DienstenLookupEvent($klant->getId());
        $event->setKlant($klant);

        $subscriber = new DienstenLookupSubscriber($em, $generator);
        $subscriber->provideDienstenInfo($event);

        $diensten = $event->getDiensten();
        $this->assertCount(1, $diensten);
        $this->assertEquals('Dagbesteding', $diensten[0]->getNaam());
        $this->assertEquals('http://www.example.com/', $diensten[0]->getUrl());
        $this->assertNull($diensten[0]->getOmschrijving());
        $this->assertEquals($aanmeldDatum, $diensten[0]->getVan());
        $this->assertEquals($afsluitDatum, $diensten[0]->getTot());
        $this->assertEquals('Trajectcoach', $diensten[0]->getTitelMedewerker());
        $this->assertEquals('Piet Pietersen', $diensten[0]->getMedewerker());
    }
}
