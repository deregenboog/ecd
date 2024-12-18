<?php

namespace AppBundle\Model;

use AppBundle\Entity\Klant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\PersistentCollection;

trait UsesKlantTrait
{
    /**
     * Method for dealing with lazy loaded Klant relations.
     * Works iterative.
     *
     * An entity that relates to Klant (ie. Deelnemers refers to Klant, and Deelname refers to Deelnemer)
     * you should implement the KlantRelationInterface which states you to implement getAppKlant() and getKlantFieldName() methods.
     * This way, we can iterative probe a Klant to see if its not disabled.
     *
     * Due to the way disabled Klant is implemented (via Doctrine Constraint Filter) it does not give you a null.
     * Symfony thinks it gets the full object, and while lazy loaded, it errors when retrieving a field of the relation (ie. in template).
     *
     * @return bool
     *
     * @throws EntityNotFoundException
     */
    public function tryLoadKlant($entity)
    {
        // Only if the requested entity has Relation to Klant
        if ($entity instanceof KlantRelationInterface) {
            try {
                // Request the klant Object. THis can be plural or single.
                $res = $entity->getKlant();

                // When single, it gives an arrayCollection.
                if ($res instanceof ArrayCollection or $res instanceof PersistentCollection) {
                    foreach ($res as $e) {
                        try {
                            $methodName = 'get'.$entity->getKlantFieldName();
                            if (!$this->tryLoadKlant($e)) {
                                $entity->{$methodName}()->removeElement($e);
                            }
                        } catch (EntityNotFoundException $exception) {
                            $entity->{$methodName}()->removeElement($e);
                        }
                    }
                }
                // If not, it can be the Klant object we want to probe.
                elseif ($res instanceof Klant) {
                    // will always throw exception due to poor constraint implementation. Non disabled will properly return...
                    return $res->getDisabled();
                }
                // Or, it refers to another relation which has perhaps has a relation to Klant. So keep trying!
                else {
                    $this->tryLoadKlant($res);
                }

                return true;
            } catch (EntityNotFoundException $e) {
                throw $e;
            }
        }

        return false;
    }
}
