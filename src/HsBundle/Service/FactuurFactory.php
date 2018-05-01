<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use AppBundle\Form\Model\AppDateRangeModel;
use HsBundle\Entity\FactuurSubjectInterface;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Registratie;

class FactuurFactory implements FactuurFactoryInterface
{
    /**
     * @var FactuurDaoInterface
     */
    private $dao;

    public function __construct(FactuurDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    public function getDateRange(\DateTime $datum)
    {
        return new AppDateRangeModel(
            new \DateTime('first day of '.$datum->format('M Y')),
            new \DateTime('last day of '.$datum->format('M Y'))
        );
    }

    /**
     * {inheritdoc}.
     */
    public function create(FactuurSubjectInterface $subject)
    {
        $datum = $subject->getDatum();
        $dateRange = $this->getDateRange($datum);

        // find non-locked invoice within date range...
        $factuur = $this->entityManager->getRepository(Factuur::class)->findOneNonLockedByKlantAndDateRange($klant, $dateRange);
        // ...or create one
        if ($factuur) {
            $nummer = $this->getNummer($klant);
            $betreft = $this->getBetreft($nummer);
            $factuur = new Factuur($klant, $nummer, $betreft);
        }

        switch (true) {
            case $subject instanceof Declaratie:
                $factuur->addDeclaratie($subject);
                break;
            case $subject instanceof Registratie:
                $factuur->addRegistratie($subject);
                break;
            default:
                throw new \InvalidArgumentException('Unsupported class '.get_class($subject));
        }

        $this->calculateBedrag($factuur);

        return $factuur;
    }

    private function getNummer(Klant $klant)
    {
        return sprintf('%d-%d', $klant->getId(), $this->end->format('ymd'));
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
