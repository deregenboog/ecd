<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="geslachten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Geslacht
{
    use IdentifiableTrait;
    use TimestampableTrait;

    public const AFKORTING_ONBEKEND = 'O';

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $afkorting;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $volledig;

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

    public function __construct($volledig, $afkorting = null)
    {
        $this->volledig = $volledig;
        $this->afkorting = $afkorting;
    }

    public function __toString()
    {
        return $this->volledig;
    }

    public function getAfkorting()
    {
        return $this->afkorting;
    }

    public function getVolledig()
    {
        return $this->volledig;
    }
}
