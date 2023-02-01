<?php

namespace MwBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\Timestampable;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="inventarisaties_verslagen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verslaginventarisatie
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var Verslag
     *
     * @ORM\ManyToOne(targetEntity="MwBundle\Entity\Verslag", inversedBy="verslaginventarisaties")
     * @ORM\JoinColumn(nullable=false, options={"default": 0})
     * @Gedmo\Versioned
     */
    private $verslag;

    /**
     * @var Inventarisatie
     *
     * @ORM\ManyToOne(targetEntity="Inventarisatie", inversedBy="verslaginventarisaties")
     * @ORM\JoinColumn(nullable=false, options={"default": 0})
     * @Gedmo\Versioned
     */
    private $inventarisatie;

    /**
     * @var Doorverwijzing
     *
     * @ORM\ManyToOne(targetEntity="Doorverwijzing")
     * @ORM\JoinColumn(name="doorverwijzer_id")
     * @Gedmo\Versioned
     */
    private $doorverwijzing;

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

    public function __construct(Verslag $verslag, Inventarisatie $inventarisatie, Doorverwijzing $doorverwijzing = null)
    {
        $this->verslag = $verslag;
        $this->inventarisatie = $inventarisatie;
        $this->doorverwijzing = $doorverwijzing;
    }

    /**
     * @return Verslag
     */
    public function getVerslag()
    {
        return $this->verslag;
    }

    /**
     * @param Verslag $verslag
     */
    public function setVerslag(Verslag $verslag = null)
    {
        $this->verslag = $verslag;

        return $this;
    }

    /**
     * @return Inventarisatie
     */
    public function getInventarisatie()
    {
        return $this->inventarisatie;
    }

    /**
     * @param Inventarisatie $inventarisatie
     */
    public function setInventarisatie($inventarisatie)
    {
        $this->inventarisatie = $inventarisatie;

        return $this;
    }

    /**
     * @return Doorverwijzing
     */
    public function getDoorverwijzing()
    {
        return $this->doorverwijzing;
    }

    /**
     * @param Doorverwijzing $doorverwijzing
     */
    public function setDoorverwijzing($doorverwijzing)
    {
        $this->doorverwijzing = $doorverwijzing;

        return $this;
    }
}
