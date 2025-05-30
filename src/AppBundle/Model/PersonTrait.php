<?php

namespace AppBundle\Model;

use AppBundle\Entity\Geslacht;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\ErrorHandler\Error\FatalError;

trait PersonTrait
{
    use NameTrait;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $roepnaam;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $geboortedatum;

    /**
     * @var Geslacht
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geslacht", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false, options={"default":0})
     *
     * @Gedmo\Versioned
     */
    protected $geslacht;

    public function __toString()
    {
        try {
            return $this->getNaam();
        } catch (EntityNotFoundException|FatalError $e) {
            return '';
        }
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
        if ($this->roepnaam) {
            $parts[] = "({$this->roepnaam})";
        }
        if ($this->tussenvoegsel) {
            $parts[] = $this->tussenvoegsel;
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

    public function getGeboortedatum()
    {
        return $this->geboortedatum;
    }

    public function setGeboortedatum(?\DateTime $geboortedatum = null)
    {
        $this->geboortedatum = $geboortedatum;

        return $this;
    }

    public function getGeslacht()
    {
        return $this->geslacht;
    }

    public function setGeslacht(?Geslacht $geslacht = null)
    {
        $this->geslacht = $geslacht;

        return $this;
    }
}
