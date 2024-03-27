<?php

namespace OekBundle\Event;

use AppBundle\Event\AbstractDienstenVrijwilligerLookupSubscriber;
use AppBundle\Event\DienstenVrijwilligerLookupEvent;
use AppBundle\Model\Dienst;
use OekBundle\Entity\Vrijwilliger;

class DienstenVrijwilligerLookupSubscriber extends AbstractDienstenVrijwilligerLookupSubscriber
{
    public function provideDienstenInfo(DienstenVrijwilligerLookupEvent $event): void
    {
        $vrijwilliger = $event->getVrijwilliger();
        $oekVrijwilliger = $this->entityManager->getRepository(Vrijwilliger::class)
            ->findOneBy(['vrijwilliger' => $vrijwilliger]);

        if ($oekVrijwilliger instanceof Vrijwilliger) {
            $dienst = new Dienst(
                'Op eigen kracht',
                $this->generator->generate('oek_vrijwilligers_view', ['id' => $oekVrijwilliger->getId()]),
                $oekVrijwilliger->getActief() ? 'actief' : 'inactief'
            );

            $event->addDienst($dienst);
        }
    }
}
