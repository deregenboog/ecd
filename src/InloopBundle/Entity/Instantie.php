<?php

namespace InloopBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimeframeableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="instanties")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Instantie
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
