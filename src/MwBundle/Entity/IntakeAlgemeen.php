<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Land;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Taal;
use AppBundle\Entity\Verblijfsstatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_intakes_algemeen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeAlgemeen extends Intake
{
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $geinformeerd;

    /**
     * @var Collection<Taal>
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Taal")
     * @ORM\JoinTable(name="mw_intakes_algemeen_talen")
     */
    private $talen;

    /**
     * @var Verblijfsstatus
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Verblijfsstatus")
     */
    private $verblijfsstatus;

    /**
     * @var string
     * @ORM\Column(name="verblijfsstatus_toelichting", type="text")
     */
    private $verblijfsstatusToelichting;

    /**
     * @var Collection<Land>
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Land")
     * @ORM\JoinTable(name="mw_intakes_algemeen_landen_rechthebbend")
     */
    private $landenRechthebbend;

    /**
     * @var string
     * @ORM\Column
     */
    private $jvg;

    /**
     * @var string
     * @ORM\Column(name="jvg_toelichting", type="text")
     */
    private $jvgToelichting;

    public function __construct(Klant $klant = null, Medewerker $medewerker = null)
    {
        parent::__construct($klant, $medewerker);
        $this->talen = new ArrayCollection();
        $this->landenRechthebbend = new ArrayCollection();
    }

    public function isGeinformeerd()
    {
        return $this->geinformeerd;
    }

    public function setGeinformeerd($geinformeerd)
    {
        $this->geinformeerd = $geinformeerd;

        return $this;
    }

    public function getTalen()
    {
        return $this->talen;
    }

    public function setTalen($talen)
    {
        $this->talen = $talen;

        return $this;
    }

    public function getVerblijfsstatus()
    {
        return $this->verblijfsstatus;
    }

    public function setVerblijfsstatus($verblijfsstatus)
    {
        $this->verblijfsstatus = $verblijfsstatus;

        return $this;
    }

    public function getVerblijfsstatusToelichting()
    {
        return $this->verblijfsstatusToelichting;
    }

    public function setVerblijfsstatusToelichting($verblijfsstatusToelichting)
    {
        $this->verblijfsstatusToelichting = $verblijfsstatusToelichting;

        return $this;
    }

    public function getLandenRechthebbend(): ?Collection
    {
        return $this->landenRechthebbend;
    }

    public function setLandenRechthebbend($landenRechthebbend): self
    {
        $this->landenRechthebbend = $landenRechthebbend;

        return $this;
    }

    public function getJvg(): ?string
    {
        return $this->jvg;
    }

    public function setJvg(string $jvg): self
    {
        $this->jvg = $jvg;

        return $this;
    }

    public function getJvgToelichting(): ?string
    {
        return $this->jvgToelichting;
    }

    public function setJvgToelichting(string $jvgToelichting): self
    {
        $this->jvgToelichting = $jvgToelichting;

        return $this;
    }
}
