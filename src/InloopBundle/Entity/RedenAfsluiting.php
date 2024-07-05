<?php

namespace InloopBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="inloop_afsluiting_redenen")
 *
 * @Gedmo\Loggable
 */
class RedenAfsluiting
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(nullable=false)
     *
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    protected $actief = true;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default":0})
     *
     * @Gedmo\Versioned
     */
    private $gewicht = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $land = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function isLand()
    {
        return $this->land;
    }

    public function setLand($land)
    {
        $this->land = $land;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }
}
