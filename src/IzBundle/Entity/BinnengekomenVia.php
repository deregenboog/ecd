<?php

namespace IzBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_via_personen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class BinnengekomenVia
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @var bool
     * @ORM\Column(name="`active`", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $actief = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $modified;
}
