<?php

namespace Tests\IzBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityManagerInterface;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\IzKlant;
use IzBundle\Event\DienstenLookupSubscriber;
use IzBundle\Repository\IzKlantRepository;
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
        $medewerker = (new Medewerker())->setVoornaam('Piet')->setAchternaam('Pietersen');
        $hulpvraag = (new Hulpvraag())->setMedewerker($medewerker);
        $datumAanmelding = new \DateTime('2024-01-01');
        $datumAfsluiting = new \DateTime('2024-02-27');
        $klant = new Klant();
        $izKlant = new IzKlant($klant);
        $izKlant->setDatumAanmelding($datumAanmelding)->setAfsluitDatum($datumAfsluiting)
            ->addHulpvraag($hulpvraag);

        $izKlantRepository = $this->createMock(IzKlantRepository::class);
        $izKlantRepository->expects($this->once())->method('findOneBy')
            ->with(['klant' => $klant])->willReturn($izKlant);
        $hulpvraagRepository = $this->createMock(IzKlantRepository::class);
        $hulpvraagRepository->expects($this->once())->method('findOneBy')
            ->with(['izKlant' => $izKlant])->willReturn($hulpvraag);
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->exactly(2))->method('getRepository')
            ->withConsecutive([IzKlant::class], [Hulpvraag::class])
            ->willReturnOnConsecutiveCalls($izKlantRepository, $hulpvraagRepository);

        $generator = $this->createMock(UrlGeneratorInterface::class);
        $generator->expects($this->once())->method('generate')->willReturn('http://www.example.com/');

        $event = new DienstenLookupEvent($klant->getId());
        $event->setKlant($klant);

        $subscriber = new DienstenLookupSubscriber($em, $generator);
        $subscriber->provideDienstenInfo($event);

        $diensten = $event->getDiensten();
        $this->assertCount(1, $diensten);
        $this->assertEquals('Informele Zorg', $diensten[0]->getNaam());
        $this->assertEquals('http://www.example.com/', $diensten[0]->getUrl());
        $this->assertNull($diensten[0]->getOmschrijving());
        $this->assertEquals($datumAanmelding, $diensten[0]->getVan());
        $this->assertEquals($datumAfsluiting, $diensten[0]->getTot());
        $this->assertEquals('coördinator', $diensten[0]->getTitelMedewerker());
        $this->assertEquals('Piet Pietersen', $diensten[0]->getMedewerker());
    }
}
