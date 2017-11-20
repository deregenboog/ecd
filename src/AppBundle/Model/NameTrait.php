<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait NameTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    protected $voornaam;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $tussenvoegsel;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $achternaam;

    public function __toString()
    {
        return $this->getNaam();
    }

    public function getNaam()
    {
        $parts = [];

        if ($this->voornaam) {
            $parts[] = $this->voornaam;
        }
        if ($this->roepnaam) {
            $parts[] = "({$this->roepnaam})";
        }
        if ($this->tussenvoegsel) {
            $parts[] = $this->tussenvoegsel;
        }
        if ($this->achternaam) {
            $parts[] = $this->achternaam;
        }

        return implode(' ', $parts);
    }

    public function getVoornaamCompleet()
    {
        $parts = [];

        if ($this->voornaam) {
            $parts[] = $this->voornaam;
        }
        if ($this->roepnaam) {
            $parts[] = "({$this->roepnaam})";
        }

        return implode(' ', $parts);
    }

    public function getAchternaamCompleet()
    {
        $parts = [];

        if ($this->tussenvoegsel) {
            $parts[] = $this->tussenvoegsel;
        }
        if ($this->achternaam) {
            $parts[] = $this->achternaam;
        }

        return implode(' ', $parts);
    }

    public function getVoornaam()
    {
        return $this->voornaam;
    }

    public function setVoornaam($voornaam)
    {
        $this->voornaam = $voornaam;

        return $this;
    }

    public function getRoepnaam()
    {
        return $this->roepnaam;
    }

    public function setRoepnaam($roepnaam)
    {
        $this->roepnaam = $roepnaam;

        return $this;
    }

    public function getTussenvoegsel()
    {
        return $this->tussenvoegsel;
    }

    public function setTussenvoegsel($tussenvoegsel)
    {
        $this->tussenvoegsel = $tussenvoegsel;

        return $this;
    }

    public function getAchternaam()
    {
        return $this->achternaam;
    }

    public function setAchternaam($achternaam)
    {
        $this->achternaam = $achternaam;

        return $this;
    }
}
