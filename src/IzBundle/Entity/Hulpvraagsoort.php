<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NamableTrait;
use AppBundle\Model\ActivatableTrait;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpvraagsoortRepository")
 * @ORM\Table(
 *     name="iz_hulpvraagsoorten",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"naam"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Hulpvraagsoort
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
