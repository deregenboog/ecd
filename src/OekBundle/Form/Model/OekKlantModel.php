<?php

namespace OekBundle\Form\Model;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekGroep;

class OekKlantModel extends OekKlant
{
//     private $oekKlant;

//     public function __construct(OekKlant $oekKlant)
//     {
//         $this->oekKlant = $oekKlant;
//     }

    public function setOekGroep(OekGroep $groep)
    {
        return $this->oekKlant->addOrkGroep($groep);
    }
}
