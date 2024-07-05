<?php

namespace MwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table("mw_afsluitredenen_klanten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class AfsluitredenKlant
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
    use TimestampableTrait;
    use NotDeletableTrait;

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
     * @ORM\Column(type="integer")
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

    public function getGewicht()
    {
        return $this->gewicht;
    }

    public function setGewicht($gewicht)
    {
        $this->gewicht = $gewicht;

        return $this;
    }

    public function isLand()
    {
        return $this->land;
    }

    public function setLand($land)
    {
        $this->land = $land;

        return $this;
    }
}
