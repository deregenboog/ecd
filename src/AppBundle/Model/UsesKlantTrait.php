<?php

namespace AppBundle\Model;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;

trait UsesKlantTrait
{

    /**
     * Method for dealing with lazy loaded Klant relations.
     * Works iterative.
     *
     * An entity that relates to Klant (ie. Deelnemers refers to Klant, and Deelname refers to Deelnemer)
     * you should implement the KlantRelationInterface which states you to implement getKlant() and getKlantFieldName() methods.
     * This way, we can iterative probe a Klant to see if its not disabled.
     *
     * Due to the way disabled Klant is implemented (via Doctrine Constraint Filter) it does not give you a null.
     * Symfony thinks it gets the full object, and while lazy loaded, it errors when retrieving a field of the relation (ie. in template).
     *
     *
     * @param $entity
     * @return bool
     * @throws EntityNotFoundException
     */
    public function tryLoadKlant($entity)
    {
        //Only if the requested entity has Relation to Klant
        if($entity instanceof KlantRelationInterface){
            try{
                //Request the klant Object. THis can be plural or single.
                $res = $entity->getKlant();

                //When single, it gives an arrayCollection.
                if($res instanceof ArrayCollection or $res instanceof PersistentCollection)
                {
                    foreach($res as $e)
                    {
                        if(!$this->tryLoadKlant($e)){
                            $methodName = "get".$entity->getKlantFieldName();

                            $entity->{$methodName}()->removeElement($e);

                        }
                    }

                }
                //If not, it can be the Klant object we want to probe.
                else if ($res instanceof Klant)
                {
                   return $res->getDisabled();
                }
                //Or, it refers to another relation which has perhaps has a relation to Klant. So keep trying!
                else
                {
                    $this->tryLoadKlant($res);
                }
                return true;

            }
            catch(EntityNotFoundException $e)
            {
                throw $e;
            }
        }
    }

}
