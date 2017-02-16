<?php

namespace OekBundle\Form\Model;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekGroep;

class OekGroepKlantModel
{
    /**
     * @var OekGroep
     */
    private $oekGroep;

    /**
     * @var OekKlant
     */
    private $oekKlant;

    public function __construct(OekGroep $oekGroep = null, OekKlant $oekKlant = null)
    {
        if (!$oekGroep && !$oekKlant) {
            throw new \RuntimeException('Groep of klant moet opgegeven worden');
        }

        $this->oekGroep = $oekGroep;
        $this->oekKlant = $oekKlant;
    }

    public function getOekKlant()
    {
        return $this->oekKlant;
    }

    public function setOekKlant(OekKlant $klant)
    {
        $this->oekGroep->addOekKlant($klant);
    }

    public function getOekGroep()
    {
        return $this->oekGroep;
    }

    public function setOekGroep(OekGroep $oekGroep)
    {
        $oekGroep->addOekKlant($this->oekKlant);
    }
}
