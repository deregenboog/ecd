<?php

namespace OekBundle\Form\Model;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekGroep;

class OekGroepFacade
{
    private $oekGroep;

    public function __construct(OekGroep $oekGroep)
    {
        $this->oekGroep = $oekGroep;
    }

    public function getOekKlanten()
    {
        return $this->oekGroep->getOekKlanten();
    }

    public function getOekKlant()
    {
        return null;
    }

    public function setOekKlant(OekKlant $klant)
    {
        $this->oekGroep->addOekKlant($klant);
    }
}
