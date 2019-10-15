<?php

namespace HsBundle\Event;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use HsBundle\Entity\Creditfactuur;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\FactuurSubjectInterface;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Registratie;
use HsBundle\Exception\InvoiceLockedException;
use Doctrine\ORM\Event\LifecycleEventArgs;

class FactuurSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof FactuurSubjectInterface) {
            $this->createOrUpdateFactuur($entity, $entityManager);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof FactuurSubjectInterface) {
            $this->createOrUpdateFactuur($entity, $entityManager);
        }
        /**
         * #906: bij updaten datum in vorige maand, werd nieuwe factuur gemaakt (viel niet in oude range).
         * Alles klopte maar ging toch fout: null als factuur in de declaratie.
         * Blijkbaar moet doctrine toch een handmatige persist hebben omdat in dit geval de basis entity al bestaat en dus geflusht kan worden, maar een gerelateerde
         * entity (factuur in dit geval) helemaal nieuw is (en dus nog geen ID heeft?)
         *
         **/

        $entityManager->persist($entity); //waarom ie nu staat. en eerst eerst niet, en het toch werkte, soms, snap ik niet. Maar heeft te maken met relaties die nieuw werden aangemaakt en niet werden opgeslagen. Makes sense?

    }

    public function preRemove($args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof FactuurSubjectInterface) {
            $factuur = $entity->getFactuur();
            if ($entity instanceof Declaratie) {
                $factuur->removeDeclaratie($entity);
            } elseif ($entity instanceof Registratie) {
                $factuur->removeRegistratie($entity);
            }
            if ($factuur->isEmpty()) {
                $entityManager->remove($factuur);
            }
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
        $factuur = $this->findOrCreateFactuur($klant, $dateRange, $entityManager);

        switch (true) {
            case $entity instanceof Declaratie:
                $entity->setFactuur($factuur);
                $factuur->addDeclaratie($entity);

                break;
            case $entity instanceof Registratie:
                $factuur->addRegistratie($entity);
                break;
            default:
                throw new \InvalidArgumentException('Unsupported class '.get_class($entity));
        }

        if ($oudeFactuur && $oudeFactuur->getId() !== $factuur->getId()) {
            if ($oudeFactuur->isDeletable()) {
                $entityManager->remove($oudeFactuur);
            }
        }

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
                '%d-%d',
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

    private function getBetreft(Klant $klant, $nummer, AppDateRangeModel $dateRange): string
    {
        return sprintf(
            'factuurnummer %s (%s, periode %s t/m %s)',
            $nummer,
            $klant->getAchternaamCompleet(),
            $dateRange->getStart()->format('d-m-Y'),
            $dateRange->getEnd()->format('d-m-Y')
        );
    }

    private function findOrCreateFactuur(
        Klant $klant,
        AppDateRangeModel $dateRange,
        EntityManager $entityManager
    ) {
        // find non-locked invoice within date range...
        $facturen = $entityManager->getRepository(Factuur::class)
            ->findNonLockedByKlantAndDateRange($klant, $dateRange);

        foreach ($facturen as $factuur) {
            if (!$factuur instanceof Creditfactuur) {
                return $factuur;
            }
        }

        // ...or create one
        $nummer = $this->getNummer($klant, $dateRange, $entityManager);
        $betreft = $this->getBetreft($klant, $nummer, $dateRange);
        $factuur = new Factuur($klant, $nummer, $betreft, $dateRange);
        $entityManager->persist($factuur);


        return $factuur;
    }
}
