<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_intakes_huisvesting")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeHuisvesting extends Intake
{
    /**
     * @var string
     * @ORM\Column(name="dakloos_duur", nullable=true)
     */
    private $dakloosDuur;

    /**
     * @var string
     * @ORM\Column(name="dakloos_oorzaak", nullable=true)
     */
    private $dakloosOorzaak;

    /**
     * @var string
     * @ORM\Column
     */
    private $huisvesting;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $inschrijfadres;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $regiobinding;

    /**
     * @var string
     * @ORM\Column(name="regiobinding_toelichting", type="text", nullable=true)
     */
    private $regiobindingToelichting;

    public function getDakloosDuur(): ?string
    {
        return $this->dakloosDuur;
    }

    public function setDakloosDuur(string $dakloosDuur): self
    {
        $this->dakloosDuur = $dakloosDuur;

        return $this;
    }

    public function getDakloosOorzaak(): ?string
    {
        return $this->dakloosOorzaak;
    }

    public function setDakloosOorzaak(string $dakloosOorzaak): self
    {
        $this->dakloosOorzaak = $dakloosOorzaak;

        return $this;
    }

    public function getHuisvesting(): ?string
    {
        return $this->huisvesting;
    }

    public function setHuisvesting(string $huisvesting): self
    {
        $this->huisvesting = $huisvesting;

        return $this;
    }

    public function getInschrijfadres(): ?string
    {
        return $this->inschrijfadres;
    }

    public function setInschrijfadres(string $inschrijfadres): self
    {
        $this->inschrijfadres = $inschrijfadres;

        return $this;
    }

    public function getRegiobinding(): ?bool
    {
        return $this->regiobinding;
    }

    public function setRegiobinding(bool $regiobinding): self
    {
        $this->regiobinding = $regiobinding;

        return $this;
    }

    public function getRegiobindingToelichting(): ?string
    {
        return $this->regiobindingToelichting;
    }

    public function setRegiobindingToelichting(string $regiobindingToelichting): self
    {
        $this->regiobindingToelichting = $regiobindingToelichting;

        return $this;
    }
}
