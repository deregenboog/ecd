<?php

namespace OekBundle\Form\Model;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekTraining;

class OekTrainingFacade
{
    private $oekTraining;

    public function __construct(OekTraining $oekTraining)
    {
        $this->oekTraining = $oekTraining;
    }

    public function getOekKlanten()
    {
        return $this->oekTraining->getOekKlanten();
    }

    public function getOekKlant()
    {
        return null;
    }

    public function setOekKlant(OekKlant $klant)
    {
        $this->oekTraining->addOekKlant($klant);
    }

    public function getOekGroep()
    {
        return $this->oekTraining->getOekGroep();
    }
}
