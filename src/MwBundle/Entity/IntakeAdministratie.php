<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_intakes_administratie")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeAdministratie extends Intake
{
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $woningnet;

    /**
     * @var string
     * @ORM\Column(name="woningnet_duur", nullable=true)
     */
    private $woningnetDuur;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $verzekerd;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $digid;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $huisarts;

    /**
     * @var string
     * @ORM\Column(name="huisarts_contactgegevens", type="text", nullable=true)
     */
    private $huisartsContactgegevens;

    /**
     * @var bool
     * @ORM\Column(name="digitaal_vaardig", type="boolean")
     */
    private $digitaalVaardig;

    public function hasWoningnet(): ?bool
    {
        return $this->woningnet;
    }

    public function setWoningnet(bool $woningnet): self
    {
        $this->woningnet = $woningnet;

        return $this;
    }

    public function getWoningnetDuur(): ?string
    {
        return $this->woningnetDuur;
    }

    public function setWoningnetDuur(string $woningnetDuur): self
    {
        $this->woningnetDuur = $woningnetDuur;

        return $this;
    }

    public function isVerzekerd(): ?bool
    {
        return $this->verzekerd;
    }

    public function setVerzekerd(bool $verzekerd): self
    {
        $this->verzekerd = $verzekerd;

        return $this;
    }

    public function hasDigid(): ?bool
    {
        return $this->digid;
    }

    public function setDigid(bool $digid): self
    {
        $this->digid = $digid;

        return $this;
    }

    public function hasHuisarts(): ?bool
    {
        return $this->huisarts;
    }

    public function setHuisarts(bool $huisarts): self
    {
        $this->huisarts = $huisarts;

        return $this;
    }

    public function getHuisartsContactgegevens(): ?string
    {
        return $this->huisartsContactgegevens;
    }

    public function setHuisartsContactgegevens(string $huisartsContactgegevens): self
    {
        $this->huisartsContactgegevens = $huisartsContactgegevens;

        return $this;
    }

    public function isDigitaalVaardig(): ?bool
    {
        return $this->digitaalVaardig;
    }

    public function setDigitaalVaardig(bool $digitaalVaardig): self
    {
        $this->digitaalVaardig = $digitaalVaardig;

        return $this;
    }
}
