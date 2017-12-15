<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\GgwGebied;
use AppBundle\Entity\Werkgebied;

trait AddressTrait
{
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $adres;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $plaats;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Werkgebied")
     * @ORM\JoinColumn(name="werkgebied", referencedColumnName="naam", nullable=true)
     * @Gedmo\Versioned
     */
    private $werkgebied;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GgwGebied")
     * @ORM\JoinColumn(name="postcodegebied", referencedColumnName="naam", nullable=true)
     * @Gedmo\Versioned
     */
    private $postcodegebied;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $mobiel;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $telefoon;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    public function getAdres()
    {
        return $this->adres;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getPlaats()
    {
        return $this->plaats;
    }

    public function getMobiel()
    {
        return $this->mobiel;
    }

    public function getTelefoon()
    {
        return $this->telefoon;
    }

    public function setAdres($adres)
    {
        $this->adres = $adres;

        return $this;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function setPlaats($plaats)
    {
        $this->plaats = $plaats;

        return $this;
    }

    public function setMobiel($mobiel)
    {
        $this->mobiel = $mobiel;

        return $this;
    }

    public function setTelefoon($telefoon)
    {
        $this->telefoon = $telefoon;

        return $this;
    }

    public function getWerkgebied()
    {
        return $this->werkgebied;
    }

    public function setWerkgebied(Werkgebied $werkgebied = null)
    {
        $this->werkgebied = $werkgebied;

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
}
