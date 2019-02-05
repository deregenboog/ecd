<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="postcodes")
 * @Gedmo\Loggable
 * @UniqueEntity("postcode")
 */
class Postcode
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @Assert\Regex(pattern="/^[0-9]{4}\s?[A-Za-z]{2}$/", message="Dit is geen geldige postcode")
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
     * @ORM\JoinColumn(name="postcodegebied", referencedColumnName="naam", nullable=true)
     * @Gedmo\Versioned
     */
    private $postcodegebied;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default" : 1})
     * @Gedmo\Versioned
     */
    private $system = true;

    public function __construct($postcode = null, Werkgebied $stadsdeel = null, GgwGebied $postcodegebied = null)
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

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getStadsdeel()
    {
        return $this->stadsdeel;
    }

    public function setStadsdeel(Werkgebied $stadsdeel)
    {
        $this->stadsdeel = $stadsdeel;

        return $this;
    }

    public function getPostcodegebied()
    {
        return $this->postcodegebied;
    }

    public function setPostcodegebied(GgwGebied $postcodegebied = null)
    {
        $this->postcodegebied = $postcodegebied;

        return $this;
    }

    public function isSystem()
    {
        return (bool) $this->system;
    }

    public function setSystem($system)
    {
        $this->system = (bool) $system;

        return $this;
    }
}
