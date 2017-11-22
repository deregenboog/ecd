<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use Doctrine\Common\Collections\Criteria;
use AppBundle\Form\Model\AppDateRangeModel;

class FactuurFactory implements FactuurFactoryInterface
{
    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    public function __construct()
    {
        $dateRange = $this->getDateRange();
        $this->start = $dateRange->getStart();
        $this->end = $dateRange->getEnd();
    }

    public function getDateRange()
    {
        return new AppDateRangeModel(
            new \DateTime('first day of previous month'),
            new \DateTime('last day of previous month')
        );
    }

    /**
     * {inheritdoc}.
     */
    public function create(Klant $klant)
    {
        $nummer = $this->getNummer($klant);
        $betreft = $this->getBetreft($nummer);
        $factuur = new Factuur($klant, $nummer, $betreft);

        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->isNull('factuur'))
            ->andWhere(Criteria::expr()->lte('datum', $this->end))
        ;

        foreach ($klant->getKlussen() as $klus) {
            foreach ($klus->getDeclaraties()->matching($criteria) as $declaratie) {
                $factuur->addDeclaratie($declaratie);
                $factuur->addKlus($klus);
            }
            foreach ($klus->getRegistraties()->matching($criteria) as $registratie) {
                $factuur->addRegistratie($registratie);
                $factuur->addKlus($klus);
            }
        }

        $this->calculateBedrag($factuur);

        return $factuur;
    }

    private function getNummer(Klant $klant)
    {
        return sprintf('%d/%d', $klant->getId(), $this->end->format('ymd'));
    }

    private function getBetreft($nummer)
    {
        return sprintf(
            'Factuurnr: %s van %s t/m %s',
            $nummer,
            $this->start->format('d-m-Y'),
            $this->end->format('d-m-Y')
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
