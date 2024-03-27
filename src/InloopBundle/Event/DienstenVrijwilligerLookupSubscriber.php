<?php

namespace InloopBundle\Event;

use AppBundle\Event\AbstractDienstenVrijwilligerLookupSubscriber;
use AppBundle\Event\DienstenVrijwilligerLookupEvent;
use AppBundle\Model\Dienst;
use InloopBundle\Entity\Vrijwilliger;

class DienstenVrijwilligerLookupSubscriber extends AbstractDienstenVrijwilligerLookupSubscriber
{
    public function provideDienstenInfo(DienstenVrijwilligerLookupEvent $event): void
    {
        $vrijwilliger = $event->getVrijwilliger();
        $inloopVrijwilliger = $this->entityManager->getRepository(Vrijwilliger::class)
            ->findOneBy(['vrijwilliger' => $vrijwilliger]);

        if ($inloopVrijwilliger instanceof Vrijwilliger) {
            $dienst = new Dienst(
                'Inloophuizen',
                $this->generator->generate('inloop_vrijwilligers_view', ['id' => $inloopVrijwilliger->getId()])
            );

            if ($inloopVrijwilliger->getAanmelddatum()) {
                $dienst->setVan($inloopVrijwilliger->getAanmelddatum());
            }

            if ($inloopVrijwilliger->getAfsluitdatum()) {
                $dienst->setTot($inloopVrijwilliger->getAfsluitdatum());
            }

            $event->addDienst($dienst);
        }
    }
}
