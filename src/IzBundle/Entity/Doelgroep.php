<?php

namespace IzBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\DoelgroepRepository")
 *
 * @ORM\Table(
 *     name="iz_doelgroepen",
 *     uniqueConstraints={
 *
 *         @ORM\UniqueConstraint(columns={"naam"})
 *     }
 * )
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Doelgroep
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

    public function __toString()
    {
        return $this->naam;
    }

    public function isDeletable()
    {
        return false;
    }
}
