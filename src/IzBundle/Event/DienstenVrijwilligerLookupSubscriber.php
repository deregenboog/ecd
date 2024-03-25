<?php

namespace IzBundle\Event;

use AppBundle\Event\AbstractDienstenVrijwilligerLookupSubscriber;
use AppBundle\Event\DienstenVrijwilligerLookupEvent;
use AppBundle\Model\Dienst;
use IzBundle\Entity\IzVrijwilliger;

class DienstenVrijwilligerLookupSubscriber extends AbstractDienstenVrijwilligerLookupSubscriber
{
    public function provideDienstenInfo(DienstenVrijwilligerLookupEvent $event): void
    {
        $vrijwilliger = $event->getVrijwilliger();
        /** @var IzVrijwilliger $izVrijwilliger */
        $izVrijwilliger = $this->entityManager->getRepository(IzVrijwilliger::class)
            ->findOneBy(['vrijwilliger' => $vrijwilliger]);

        if ($izVrijwilliger) {
            $dienst = new Dienst(
                'Informele Zorg',
                $this->generator->generate('iz_vrijwilligers_view', ['id' => $izVrijwilliger->getId()])
            );

            if ($izVrijwilliger->getDatumAanmelding()) {
                $dienst->setVan($izVrijwilliger->getDatumAanmelding());
            }

            if ($izVrijwilliger->getAfsluitDatum()) {
                $dienst->setTot($izVrijwilliger->getAfsluitDatum());
            }

            if (count($izVrijwilliger->getHulpaanbiedingen()) > 0) {
                $laatsteHulpaanbod = $izVrijwilliger->getHulpaanbiedingen()[0];
                if ($laatsteHulpaanbod->getMedewerker()) {
                    $dienst
                        ->setTitelMedewerker('coördinator')
                        ->setMedewerker($laatsteHulpaanbod->getMedewerker())
                    ;
                }
            }

            $event->addDienst($dienst);
        }
    }
}
