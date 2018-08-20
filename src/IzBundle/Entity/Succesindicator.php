<?php

namespace IzBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="iz_succesindicatoren",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"naam", "discr"})
 *     }
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "financieel" = "SuccesindicatorFinancieel",
 *     "participatie" = "SuccesindicatorParticipatie",
 *     "persoonlijk" = "SuccesindicatorPersoonlijk"
 * })
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
abstract class Succesindicator
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait;

    public function __toString()
    {
        return $this->naam;
    }

    public function isDeletable()
    {
        return false;
    }
}
