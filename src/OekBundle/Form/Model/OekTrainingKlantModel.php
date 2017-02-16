<?php

namespace OekBundle\Form\Model;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekTraining;

class OekTrainingKlantModel
{
    /**
     * @var OekTraining
     */
    private $oekTraining;

    /**
     * @var OekKlant
     */
    private $oekKlant;

    public function __construct(OekTraining $oekTraining = null, OekKlant $oekKlant = null)
    {
        if (!$oekTraining && !$oekKlant) {
            throw new \RuntimeException('Training of klant moet opgegeven worden');
        }

        $this->oekTraining = $oekTraining;
        $this->oekKlant = $oekKlant;
    }

    public function getOekKlant()
    {
        return $this->oekKlant;
    }

    public function setOekKlant(OekKlant $klant)
    {
        $this->oekTraining->addOekKlant($klant);
    }

    public function getOekTraining()
    {
        return $this->oekTraining;
    }

    public function setOekTraining(OekTraining $oekTraining)
    {
        $oekTraining->addOekKlant($this->oekKlant);
    }
}
