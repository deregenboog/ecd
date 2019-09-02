<?php

namespace AppBundle\Model;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityNotFoundException;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait UsesKlantTrait
{

    public function tryLoadKlant($entity)
    {
        if($this->klantPropertyName != ''){
            try{
                $entity->{"get".$this->klantPropertyName}()->getDisabled();

            }
            catch(EntityNotFoundException $e)
            {
                throw $e;
            }
        }
    }

}
