<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use InloopBundle\Entity\Locatie;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;

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
