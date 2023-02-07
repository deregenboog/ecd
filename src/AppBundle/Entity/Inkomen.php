<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimeframeableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="inkomens")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Inkomen
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimeframeableTrait;
    use TimestampableTrait;

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
