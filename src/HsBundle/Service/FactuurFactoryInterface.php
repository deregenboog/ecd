<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use AppBundle\Form\Model\AppDateRangeModel;

interface FactuurFactoryInterface
{
    /**
     * @param Klant $klant
     *
     * @return Factuur
     */
    public function create(Klant $klant);

    /**
     * @return AppDateRangeModel
     */
    public function getDateRange();
}
