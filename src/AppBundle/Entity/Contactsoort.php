<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="contactsoorts")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Contactsoort
{
    use IdentifiableTrait;

    /**
     * @ORM\Column(name="text", type="string")
     * @Gedmo\Versioned
     */
    private $naam;

    public function __toString()
    {
        return (string) $this->naam;
    }
}
