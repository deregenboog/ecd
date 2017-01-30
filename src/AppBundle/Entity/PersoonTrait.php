<?php

namespace AppBundle\Entity;

trait PersoonTrait
{
    /**
     * @Column(type="string", length=255, nullable=true)
     */
    private $voornaam;

    /**
     * @Column(type="string", nullable=true)
     */
    private $tussenvoegsel;

    /**
     * @Column(type="string")
     */
    private $achternaam;

    public function __toString()
    {
        return $this->getNaam();
    }

    public function getNaam()
    {
        $parts = [];

        if ($this->achternaam) {
            if ($this->voornaam || $this->tussenvoegsel || $this->roepnaam) {
                $parts[] = $this->achternaam.',';
            } else {
                $parts[] = $this->achternaam;
            }
        }
        if ($this->voornaam) {
            $parts[] = $this->voornaam;
        }
        if ($this->tussenvoegsel) {
            $parts[] = $this->tussenvoegsel;
        }
        if ($this->roepnaam) {
            $parts[] = "({$this->roepnaam})";
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
