<?php

namespace MwBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * @ORM\Entity
 * @ORM\Table(name="inventarisaties_verslagen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verslaginventarisatie
{
    use IdentifiableTrait, Timestampable;

    /**
     * @var Verslag
     *
     * @ORM\ManyToOne(targetEntity="MwBundle\Entity\Verslag", inversedBy="verslaginventarisaties")
     * @Gedmo\Versioned
     */
    private $verslag;

    /**
     * @var Inventarisatie
     *
     * @ORM\ManyToOne(targetEntity="Inventarisatie", inversedBy="verslaginventarisaties")
     * @Gedmo\Versioned
     */
    private $inventarisatie;

    /**
     * @var Doorverwijzer
     *
     * @ORM\ManyToOne(targetEntity="Doorverwijzer")
     * @Gedmo\Versioned
     */
    private $doorverwijzer;

    public function __construct(Verslag $verslag, Inventarisatie $inventarisatie, Doorverwijzer $doorverwijzer = null)
    {
        $this->verslag = $verslag;
        $this->inventarisatie = $inventarisatie;
        $this->doorverwijzer = $doorverwijzer;
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
     * @return Doorverwijzer
     */
    public function getDoorverwijzer()
    {
        return $this->doorverwijzer;
    }

    /**
     * @param Doorverwijzer $doorverwijzer
     */
    public function setDoorverwijzer($doorverwijzer)
    {
        $this->doorverwijzer = $doorverwijzer;

        return $this;
    }
}
