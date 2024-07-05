<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="landen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Land
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(name="AFK2", type="string", length=5)
     *
     * @Gedmo\Versioned
     */
    private $afkorting2;

    /**
     * @ORM\Column(name="AFK3", type="string", length=5)
     *
     * @Gedmo\Versioned
     */
    private $afkorting3;

    /**
     * @ORM\Column(type="string")
     *
     * @Gedmo\Versioned
     */
    private $land;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct($land = null, $afkorting2 = '', $afkorting3 = '')
    {
        $this->land = $land;
        $this->afkorting2 = $afkorting2;
        $this->afkorting3 = $afkorting3;
    }

    public function __toString()
    {
        return $this->land;
    }

    public function getAfkorting2()
    {
        return $this->afkorting2;
    }

    public function getAfkorting3()
    {
        return $this->afkorting3;
    }

    public function getNaam()
    {
        return $this->land;
    }

    public function setNaam(string $naam)
    {
        $this->land = $naam;

        return $this;
    }
}
