<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_intakes_welzijn")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeWelzijn extends Intake
{
    /**
     * @var string
     * @ORM\Column(name="sociaal_netwerk", type="text", nullable=true)
     */
    private $sociaalNetwerk;

    /**
     * @var bool
     * @ORM\Column(name="sociaal_netwerk_bekend", type="boolean")
     */
    private $sociaalNetwerkBekend;

    /**
     * @var string
     * @ORM\Column(name="sociaal_netwerk_contactgegevens", type="text", nullable=true)
     */
    private $sociaalNetwerkContactgegevens;

    /**
     * @var bool
     * @ORM\Column(name="andere_instanties", type="boolean")
     */
    private $andereInstanties;

    /**
     * @var string
     * @ORM\Column(name="andere_instanties_contactgegevens", type="text", nullable=true)
     */
    private $andereInstantiesContactgegevens;

    /**
     * @var bool
     * @ORM\Column(name="dagstructuur", type="boolean")
     */
    private $dagstructuur;

    /**
     * @var string
     * @ORM\Column(name="dagstructuur_toelichting", type="text", nullable=true)
     */
    private $dagstructuurToelichting;

    /**
     * @var bool
     * @ORM\Column(name="fysiek_in_orde", type="boolean")
     */
    private $fysiekInOrde;

    /**
     * @var string
     * @ORM\Column(name="fysiek_toelichting", type="text", nullable=true)
     */
    private $fysiekToelichting;

    /**
     * @var bool
     * @ORM\Column(name="psychische_problemen", type="boolean")
     */
    private $psychischeProblemen;

    /**
     * @var string
     * @ORM\Column(name="psychische_problemen_toelichting", type="text", nullable=true)
     */
    private $psychischeProblemenToelichting;

    /**
     * @var array
     * @ORM\Column(type="simple_array")
     */
    private $verslaving;

    /**
     * @var string
     * @ORM\Column(name="verslaving_toelichting", type="text", nullable=true)
     */
    private $verslavingToelichting;

    /**
     * @var string
     * @ORM\Column(name="justitie", type="boolean")
     */
    private $justitie;

    /**
     * @var string
     * @ORM\Column(name="justitie_toelichting", type="text", nullable=true)
     */
    private $justitieToelichting;

    public function getSociaalNetwerk(): ?string
    {
        return $this->sociaalNetwerk;
    }

    public function setSociaalNetwerk(string $sociaalNetwerk): self
    {
        $this->sociaalNetwerk = $sociaalNetwerk;

        return $this;
    }

    public function getSociaalNetwerkBekend(): ?bool
    {
        return $this->sociaalNetwerkBekend;
    }

    public function setSociaalNetwerkBekend(bool $sociaalNetwerkBekend): self
    {
        $this->sociaalNetwerkBekend = $sociaalNetwerkBekend;

        return $this;
    }

    public function getSociaalNetwerkContactgegevens(): ?string
    {
        return $this->sociaalNetwerkContactgegevens;
    }

    public function setSociaalNetwerkContactgegevens(string $sociaalNetwerkContactgegevens): self
    {
        $this->sociaalNetwerkContactgegevens = $sociaalNetwerkContactgegevens;

        return $this;
    }

    public function hasAndereInstanties(): ?bool
    {
        return $this->andereInstanties;
    }

    public function setAndereInstanties(bool $andereInstanties): self
    {
        $this->andereInstanties = $andereInstanties;

        return $this;
    }

    public function getAndereInstantiesContactgegevens(): ?string
    {
        return $this->andereInstantiesContactgegevens;
    }

    public function setAndereInstantiesContactgegevens(string $andereInstantiesContactgegevens): self
    {
        $this->andereInstantiesContactgegevens = $andereInstantiesContactgegevens;

        return $this;
    }

    public function hasDagstructuur(): ?bool
    {
        return $this->dagstructuur;
    }

    public function setDagstructuur(bool $dagstructuur): self
    {
        $this->dagstructuur = $dagstructuur;

        return $this;
    }

    public function getDagstructuurToelichting(): ?string
    {
        return $this->dagstructuurToelichting;
    }

    public function setDagstructuurToelichting(string $dagstructuurToelichting): self
    {
        $this->dagstructuurToelichting = $dagstructuurToelichting;

        return $this;
    }

    public function isFysiekInOrde(): ?bool
    {
        return $this->fysiekInOrde;
    }

    public function setFysiekInOrde(bool $fysiekInOrde): self
    {
        $this->fysiekInOrde = $fysiekInOrde;

        return $this;
    }

    public function getFysiekToelichting(): ?string
    {
        return $this->fysiekToelichting;
    }

    public function setFysiekToelichting(string $fysiekToelichting): self
    {
        $this->fysiekToelichting = $fysiekToelichting;

        return $this;
    }

    public function hasPsychischeProblemen(): ?bool
    {
        return $this->psychischeProblemen;
    }

    public function setPsychischeProblemen(bool $psychischeProblemen): self
    {
        $this->psychischeProblemen = $psychischeProblemen;

        return $this;
    }

    public function getPsychischeProblemenToelichting(): ?string
    {
        return $this->psychischeProblemenToelichting;
    }

    public function setPsychischeProblemenToelichting(string $psychischeProblemenToelichting): self
    {
        $this->psychischeProblemenToelichting = $psychischeProblemenToelichting;

        return $this;
    }

    public function getVerslaving(): array
    {
        return (array) $this->verslaving;
    }

    public function setVerslaving(array $verslaving): self
    {
        $this->verslaving = $verslaving;

        return $this;
    }

    public function getVerslavingToelichting(): ?string
    {
        return $this->verslavingToelichting;
    }

    public function setVerslavingToelichting(string $verslavingToelichting): self
    {
        $this->verslavingToelichting = $verslavingToelichting;

        return $this;
    }

    public function hasJustitie(): ?bool
    {
        return $this->justitie;
    }

    public function setJustitie(bool $justitie): self
    {
        $this->justitie = $justitie;

        return $this;
    }

    public function getJustitieToelichting(): ?string
    {
        return $this->justitieToelichting;
    }

    public function setJustitieToelichting(string $justitieToelichting): self
    {
        $this->justitieToelichting = $justitieToelichting;

        return $this;
    }
}
