<?php

namespace HsBundle\Entity;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use HsBundle\Exception\InvoiceLockedException;
use HsBundle\Exception\InvoiceNotLockedException;

class FactuurSubjectHelper
{
    private $entityManager;
    /**
     * @param FactuurSubjectInterface $entity
     * @param EntityManagerInterface $entityManager
     * @throws \HsBundle\Exception\InvoiceLockedException
     */
    public function beforeUpdateEntity($entity, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $klant = $entity->getKlus()->getKlant();

        // no customer, no invoice
        if (!$klant) {
            return;
        }

        $oudeFactuur = $entity->getFactuur();
        $dateRange = $this->getDateRange($entity->getDatum());

        /**
         * If entity has a factuur, than it is an edit and not a new factuur.
         * Fields that can be editted are also dateRange. Thus, we will not find an invoice.
         */
        $factuur = $this->findOrCreateFactuur($klant, $dateRange, $entityManager);
        if ($entity instanceof Declaratie) {
            $factuur->addDeclaratie($entity);
        } elseif ($entity instanceof Registratie) {
            $factuur->addRegistratie($entity);
        }


        /**
         * If oudeFactuur is not the same, then the old factuur must be saved manually because there is no relation anymore with the basic entity.
         */
        if (null !== $oudeFactuur && $oudeFactuur->getId() !== $factuur->getId()) {
            if ($entity instanceof Declaratie) {
                $oudeFactuur->removeDeclaratie($entity);
            } elseif ($entity instanceof Registratie) {
                $oudeFactuur->removeRegistratie($entity);
            }

            if ($oudeFactuur->getBedrag() == 0) {
                $entityManager->remove($oudeFactuur);
            } else {
                $entityManager->persist(($oudeFactuur));
            }
        }
    }

    private function getDateRange(\DateTime $date)
    {
        return new AppDateRangeModel(
            new \DateTime('first day of '.$date->format('M Y')),
            new \DateTime('last day of '.$date->format('M Y'))
        );
//        return new AppDateRangeModel(
//            new \DateTime('first day of january '.$date->format('Y')),
//            new \DateTime('last day of december '.$date->format('Y'))
//        );
    }

    private function findOrCreateFactuur(
        Klant $klant,
        AppDateRangeModel $dateRange,
        EntityManagerInterface $entityManager
    ) {
        // find non-locked invoice within date range...
        $facturen = $entityManager->getRepository(Factuur::class)
            ->findNonLockedByKlantAndDateRange($klant, $dateRange);

        $factuur = null;
        foreach ($facturen as $f) {
            if ($f instanceof Creditfactuur) {
                continue;
            }
            $factuur = $f;
        }
        if (null !== $factuur) {
            return $factuur;
        }

        // ...or create one
        $nummer = $this->getNummer($klant, $dateRange, $entityManager);
        $betreft = $this->getBetreft($klant, $nummer, $dateRange);
        $factuur = new Factuur($klant, $nummer, $betreft, $dateRange);

        return $factuur;
    }

    private function getNummer(Klant $klant, AppDateRangeModel $dateRange, EntityManagerInterface $entityManager)
    {
        $generateNummer = function (Klant $klant, AppDateRangeModel $dateRange) {
            return sprintf(
                '%d-%d',
                $klant->getId(),
                $dateRange->getEnd()->format('ymd')
            );
        };

        $facturen = $entityManager->getRepository(Factuur::class)->findByKlantAndDateRange($klant, $dateRange);
        if (0 === (is_array($facturen) || $facturen instanceof \Countable ? count($facturen) : 0)) {
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
}
