<?php

namespace AppBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="landen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Land
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="AFK2", type="string", length=5, nullable=false)
     * @Gedmo\Versioned
     */
    private $afkorting2;

    /**
     * @ORM\Column(name="AFK3", type="string", length=5, nullable=false)
     * @Gedmo\Versioned
     */
    private $afkorting3;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $land;

    public function __construct($land = null, $afkorting2 = null, $afkorting3 = null)
    {
        $this->land = $land;
        $this->afkorting2 = $afkorting2;
        $this->afkorting3 = $afkorting3;
    }

    public function __toString()
    {
        return $this->land;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAfkorting()
    {
        return $this->afkorting;
    }

    public function getNaam()
    {
        return $this->land;
    }
}
