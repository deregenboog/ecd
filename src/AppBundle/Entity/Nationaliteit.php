<?php

namespace AppBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="nationaliteiten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Nationaliteit
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $afkorting;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $naam;

    public function __construct($naam = null, $afkorting = null)
    {
        $this->naam = $naam;
        $this->afkorting = $afkorting;
    }

    public function __toString()
    {
        return $this->naam;
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
        return $this->naam;
    }
}
