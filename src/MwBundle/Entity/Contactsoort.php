<?php

namespace MwBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
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
    use NameableTrait;

    /**
     * @ORM\Column(name="text", type="string")
     * @Gedmo\Versioned
     */
    protected $naam;
}
