<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Entity\Verslag;
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Verslag")
     * @Gedmo\Versioned
     */
    private $verslag;

    /**
     * @var Inventarisatie
     *
     * @ORM\ManyToOne(targetEntity="Inventarisatie")
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
    public function setVerslag(Verslag $verslag)
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
