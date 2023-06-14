<?php

namespace GaBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="ga_redenen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Reden
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(length=100, nullable=true)
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;
}
