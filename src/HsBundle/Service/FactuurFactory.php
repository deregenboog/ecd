<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Klant;
use Doctrine\Common\Collections\Criteria;

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
        $this->start = new \DateTime('first day of previous month');
        $this->end = new \DateTime('last day of previous month');
    }

    /**
     * {inheritdoc}
     */
    public function create(Klant $klant)
    {
        $nummer = sprintf('%d/%d', $klant->getId(), $this->end->format('ymd'));
        $betreft = sprintf(
            'Factuurnr: %s van %s t/m %s',
            $nummer,
            $this->start->format('d-m-Y'),
            $this->end->format('d-m-Y')
        );

        $factuur = new Factuur($klant, $nummer, $betreft);

        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('factuur'))
            ->andWhere(Criteria::expr()->lte('datum', $this->end))
        ;

        foreach ($klant->getKlussen() as $klus) {
            foreach ($klus->getDeclaraties()->matching($criteria) as $declaratie) {
                $factuur->addDeclaratie($declaratie);
            }
            foreach ($klus->getRegistraties()->matching($criteria) as $registratie) {
                $factuur->addRegistratie($registratie);
                if (!$factuur->getKlussen()->contains($klus)) {
                    $factuur->getKlussen()->add($klus);
                }
            }
        }

        $this->calculateBedrag($factuur);

        return $factuur;
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
