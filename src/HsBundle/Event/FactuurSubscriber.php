<?php

namespace HsBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use HsBundle\Entity\FactuurSubjectInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Registratie;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\PostFlushEventArgs;

class FactuurSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist($args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof FactuurSubjectInterface) {
            $this->createOrUpdateFactuur($entity, $args->getEntityManager());
        }
    }

    public function postUpdate($args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof FactuurSubjectInterface) {
            $this->createOrUpdateFactuur($entity, $args->getEntityManager());
        }
    }

    private function createOrUpdateFactuur(FactuurSubjectInterface $entity, EntityManager $entityManager)
    {
        $klant = $entity->getKlus()->getKlant();

        // no customer, no invoice
        if (!$klant) {
            return;
        }

        $dateRange = $this->getDateRange($entity->getDatum());
        $oudeFactuur = $entity->getFactuur();

        // find non-locked invoice within date range...
        $facturen = $entityManager->getRepository(Factuur::class)->findNonLockedByKlantAndDateRange($klant, $dateRange);
        $factuur = count($facturen) > 0 ? $facturen[0] : null;
        // ...or create one
        if (!$factuur) {
            $nummer = $this->getNummer($klant, $dateRange, $entityManager);
            $betreft = $this->getBetreft($nummer, $dateRange);
            $factuur = new Factuur($klant, $nummer, $betreft);
            $entityManager->persist($factuur);
        }

        switch (true) {
            case $entity instanceof Declaratie:
                $factuur->addDeclaratie($entity);
                break;
            case $entity instanceof Registratie:
                $factuur->addRegistratie($entity);
                break;
            default:
                throw new \InvalidArgumentException('Unsupported class '.get_class($subject));
        }

        $this->calculateBedrag($factuur);
        if ($oudeFactuur) {
            $this->calculateBedrag($oudeFactuur);
            if ($oudeFactuur->isDeletable()) {
                $entityManager->remove($oudeFactuur);
            }
        }

        $entityManager->flush();
    }

    private function getDateRange(\DateTime $date)
    {
        return new AppDateRangeModel(
            new \DateTime('first day of '.$date->format('M Y')),
            new \DateTime('last day of '.$date->format('M Y'))
        );
    }

    private function getNummer(Klant $klant, AppDateRangeModel $dateRange, EntityManager $entityManager)
    {
        $generateNummer = function (Klant $klant, AppDateRangeModel $dateRange) {
            return sprintf(
                '%d/%d',
                $klant->getId(),
                $dateRange->getEnd()->format('ymd')
            );
        };

        $facturen = $entityManager->getRepository(Factuur::class)->findByKlantAndDateRange($klant, $dateRange);
        if (0 === count($facturen)) {
            return $generateNummer($klant, $dateRange);
        }

        $nummers = [];
        foreach ($facturen as $factuur) {
            $nummers[] = $factuur->getNummer();
        }

        $i = 1;
        while (true) {
            $nummer = sprintf('%s/%d', $generateNummer($klant, $dateRange), ++$i);
            if (!in_array($nummer, $nummers)) {
                return $nummer;
            }
        }
    }

    private function getBetreft($nummer, AppDateRangeModel $dateRange)
    {
        return sprintf(
            'Factuurnr: %s van %s t/m %s',
            $nummer,
            $dateRange->getStart()->format('d-m-Y'),
            $dateRange->getEnd()->format('d-m-Y')
        );
    }

    private function calculateBedrag(Factuur $factuur)
    {
        $bedrag = 0.0;

        foreach ($factuur->getDeclaraties() as $declaratie) {
            $bedrag += $declaratie->getBedrag();
        }

        foreach ($factuur->getRegistraties() as $registratie) {
            $bedrag += 2.5 * $registratie->getUren();
        }

        $factuur->setBedrag($bedrag);

        return $this;
    }
}
