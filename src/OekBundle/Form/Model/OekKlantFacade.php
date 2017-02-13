<?php

namespace OekBundle\Form\Model;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekGroep;
use OekBundle\Entity\OekTraining;

class OekKlantFacade
{
    private $oekKlant;

    public function __construct(OekKlant $oekKlant)
    {
        $this->oekKlant = $oekKlant;
    }

    public function __toString()
    {
        return $this->oekKlant->__toString();
    }

    public function getOekGroepen()
    {
        return $this->oekKlant->getOekGroepen();
    }

    public function getOekGroep()
    {
        return null;
    }

    public function setOekGroep(OekGroep $groep)
    {
        $groep->addOekKlant($this->oekKlant);

        return $this;
    }

    public function getOekTrainingen()
    {
        return $this->oekKlant->getOekTrainingen();
    }

    public function getOekTraining()
    {
        return null;
    }

    public function setOekTraining(OekTraining $training)
    {
        $training->addOekKlant($this->oekKlant);

        return $this;
    }
}
