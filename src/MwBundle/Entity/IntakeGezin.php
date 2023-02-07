<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_intakes_gezin")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeGezin extends Intake
{
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $gezin;

    /**
     * @var bool
     * @ORM\Column(name="dreigende_dakloosheid", type="boolean")
     */
    private $dreigendeDakloosheid;

    /**
     * @var string
     * @ORM\Column(name="dreigende_dakloosheid_toelichting", type="text", nullable=true)
     */
    private $dreigendeDakloosheidToelichting;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $mog;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $regiobinding;

    /**
     * @var string
     * @ORM\Column(name="regiobinding_toelichting", type="text", nullable=true)
     */
    private $regiobindingToelichting;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $okt;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $hulpverleningKinderen;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $kinderbijslag;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $kindgebondenBudget;

    public function isGezin(): ?bool
    {
        return $this->gezin;
    }

    public function setGezin(bool $gezin): self
    {
        $this->gezin = $gezin;

        return $this;
    }

    public function hasDreigendeDakloosheid(): ?bool
    {
        return $this->dreigendeDakloosheid;
    }

    public function setDreigendeDakloosheid(bool $dreigendeDakloosheid): self
    {
        $this->dreigendeDakloosheid = $dreigendeDakloosheid;

        return $this;
    }

    public function getDreigendeDakloosheidToelichting(): ?string
    {
        return $this->dreigendeDakloosheidToelichting;
    }

    public function setDreigendeDakloosheidToelichting(string $dreigendeDakloosheidToelichting): self
    {
        $this->dreigendeDakloosheidToelichting = $dreigendeDakloosheidToelichting;

        return $this;
    }

    public function getMog(): ?bool
    {
        return $this->mog;
    }

    public function setMog(bool $mog): self
    {
        $this->mog = $mog;

        return $this;
    }

    public function hasRegiobinding(): ?bool
    {
        return $this->regiobinding;
    }

    public function setRegiobinding(bool $regiobinding): self
    {
        $this->regiobinding = $regiobinding;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegiobindingToelichting(): ?string
    {
        return $this->regiobindingToelichting;
    }

    public function setRegiobindingToelichting(string $regiobindingToelichting): self
    {
        $this->regiobindingToelichting = $regiobindingToelichting;

        return $this;
    }

    public function getOkt(): ?bool
    {
        return $this->okt;
    }

    public function setOkt(bool $okt): self
    {
        $this->okt = $okt;

        return $this;
    }

    public function hasHulpverleningKinderen(): ?bool
    {
        return $this->hulpverleningKinderen;
    }

    public function setHulpverleningKinderen(bool $hulpverleningKinderen): self
    {
        $this->hulpverleningKinderen = $hulpverleningKinderen;

        return $this;
    }

    public function hasKinderbijslag(): ?bool
    {
        return $this->kinderbijslag;
    }

    public function setKinderbijslag(bool $kinderbijslag): self
    {
        $this->kinderbijslag = $kinderbijslag;

        return $this;
    }

    public function hasKindgebondenBudget(): ?bool
    {
        return $this->kindgebondenBudget;
    }

    public function setKindgebondenBudget(bool $kindgebondenBudget): self
    {
        $this->kindgebondenBudget = $kindgebondenBudget;

        return $this;
    }
}
