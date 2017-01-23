<?php

namespace OekBundle\Form\Model;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekGroep;
use OekBundle\Entity\OekTraining;

class OekKlantModel
{
    private $oekKlant;

    public function __construct(OekKlant $oekKlant)
    {
        $this->oekKlant = $oekKlant;
    }

    public function getOekGroep()
    {
        return null;
    }

    public function getOekGroepen()
    {
        return $this->oekKlant->getOekGroepen();
    }

    public function setOekGroep(OekGroep $groep)
    {
        $groep->addOekKlant($this->oekKlant);
    }

    public function getOekTraining()
    {
        return null;
    }

    public function getOekTrainingen()
    {
        return $this->oekKlant->getOekTrainingen();
    }

    public function setOekTraining(OekTraining $training)
    {
        $training->addOekKlant($this->oekKlant);
    }

    public function getOekGroepsTrainingen()
    {
        $trainingen = [];

        foreach ($this->oekKlant->getOekGroepen() as $groep) {
            $trainingen = array_merge($trainingen, $groep->getOekTrainingen()->toArray());
        }

        return $trainingen;
    }
}
