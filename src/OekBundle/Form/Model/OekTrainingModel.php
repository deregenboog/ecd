<?php

namespace OekBundle\Form\Model;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekTraining;

class OekTrainingModel
{
    private $oekTraining;

    public function __construct(OekTraining $oekTraining)
    {
        $this->oekTraining = $oekTraining;
    }

    public function getOekKlant()
    {
        return null;
    }

    public function getOekKlanten()
    {
        return $this->oekTraining->getOekKlanten()->toArray() ?: [0];
    }

    public function setOekKlant(OekKlant $klant)
    {
        $this->oekTraining->addOekKlant($klant);
    }

    public function getOekGroepsKlanten()
    {
        return $this->oekTraining->getOekGroep()->getOekKlanten()->toArray() ?: [0];
    }
}
