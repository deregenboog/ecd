<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\IdentifiableTrait;
use AppBundle\Entity\NamableTrait;
use AppBundle\Entity\ActivatableTrait;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\DoelgroepRepository")
 * @ORM\Table(
 *     name="iz_doelgroepen",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="unique_naam_idx", columns={"naam"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Doelgroep
{
    use IdentifiableTrait, NamableTrait, ActivatableTrait;

    public function __toString()
    {
        return $this->naam;
    }

    public function isDeletable()
    {
        return false;
    }
}
