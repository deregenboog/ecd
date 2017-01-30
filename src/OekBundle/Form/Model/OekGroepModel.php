<?php

namespace OekBundle\Form\Model;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekGroep;

class OekGroepModel
{
    private $oekGroep;

    public function __construct(OekGroep $oekGroep)
    {
        $this->oekGroep = $oekGroep;
    }

    public function getOekKlant()
    {
        return null;
    }

    public function getOekKlanten()
    {
        return $this->oekGroep->getOekKlanten()->toArray() ?: [0];
    }

    public function setOekKlant(OekKlant $klant)
    {
        $this->oekGroep->addOekKlant($klant);
    }
}
