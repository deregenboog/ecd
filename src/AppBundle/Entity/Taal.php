<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
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
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;

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

    public function __construct($naam = null, $afkorting = '', $favoriet=null)
    {
        $this->naam = $naam;
        $this->afkorting = $afkorting;
        $this->favoriet = $favoriet;
    }

    public function getAfkorting()
    {
        return $this->afkorting;
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
