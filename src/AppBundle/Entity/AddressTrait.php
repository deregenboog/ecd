<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait AddressTrait
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $adres;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $plaats;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mobiel;

    /**
     * @ORM\Column(type="string", nullable=true)
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
}
