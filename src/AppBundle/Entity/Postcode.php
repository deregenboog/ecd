<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="postcodes")
 * @Gedmo\Loggable
 */
class Postcode
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $postcode;

    /**
     * @ORM\ManyToOne(targetEntity="Werkgebied")
     * @ORM\JoinColumn(name="stadsdeel", referencedColumnName="naam", nullable=false)
     * @Gedmo\Versioned
     */
    private $stadsdeel;

    /**
     * @ORM\ManyToOne(targetEntity="GgwGebied")
     * @ORM\JoinColumn(name="postcodegebied", referencedColumnName="naam", nullable=false)
     * @Gedmo\Versioned
     */
    private $postcodegebied;

    public function __construct($postcode, Werkgebied $stadsdeel, GgwGebied $postcodegebied = null)
    {
        $this->postcode = $postcode;
        $this->stadsdeel = $stadsdeel;
        $this->postcodegebied = $postcodegebied;
    }

    public function __toString()
    {
        return $this->postcode;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getStadsdeel()
    {
        return $this->stadsdeel;
    }

    public function getPostcodegebied()
    {
        return $this->postcodegebied;
    }
}
