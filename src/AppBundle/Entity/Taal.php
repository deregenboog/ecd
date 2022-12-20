<?php

namespace AppBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="talen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Taal
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     * @var bool
     */
    private $favoriet = false;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $afkorting = '';

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $naam;

    public function __construct($naam = null, $afkorting = '', $favoriet=null)
    {
        $this->naam = $naam;
        $this->afkorting = $afkorting;
        $this->favoriet = $favoriet;
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

    public function isFavoriet(): bool
    {
        return (bool) $this->favoriet;
    }

    public function setFavoriet(bool $favoriet): self
    {
        $this->favoriet = $favoriet;

        return $this;
    }
}
