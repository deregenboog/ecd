<?php

namespace GaBundle\Event;

use AppBundle\Event\AbstractDienstenVrijwilligerLookupSubscriber;
use AppBundle\Event\DienstenVrijwilligerLookupEvent;
use AppBundle\Model\Dienst;
use GaBundle\Entity\Vrijwilligerdossier;

class DienstenVrijwilligerLookupSubscriber extends AbstractDienstenVrijwilligerLookupSubscriber
{
    public function provideDienstenInfo(DienstenVrijwilligerLookupEvent $event): void
    {
        $vrijwilliger = $event->getVrijwilliger();
        $dossier = $this->entityManager->getRepository(Vrijwilligerdossier::class)
            ->findOneBy(['vrijwilliger' => $vrijwilliger]);

        if ($dossier instanceof Vrijwilligerdossier) {
            $dienst = new Dienst(
                'Groepsactiviteiten',
                $this->generator->generate('ga_vrijwilligerdossiers_view', ['id' => $dossier->getId()])
            );
            $dienst->setVan($dossier->getAanmelddatum());

            if ($dossier->getAfsluitdatum()) {
                $dienst->setTot($dossier->getAfsluitdatum());
            }

            $event->addDienst($dienst);
        }
    }
}
