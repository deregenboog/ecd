<?php

namespace IzBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="iz_succesindicatoren",
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
class Succesindicator
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

    /**
     * @deprecated
     *
     * @ORM\Column(type="string", length=255)
     */
    private $discr;

    public function isDeletable()
    {
        return false;
    }
}
