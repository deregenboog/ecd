<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_intakes_verwachting")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeVerwachting extends Intake
{
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $verwachtingen;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $plannen;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $actiepunten;

    public function getVerwachtingen(): ?string
    {
        return $this->verwachtingen;
    }

    public function setVerwachtingen(string $verwachtingen): self
    {
        $this->verwachtingen = $verwachtingen;

        return $this;
    }

    public function getPlannen(): ?string
    {
        return $this->plannen;
    }

    public function setPlannen(string $plannen): self
    {
        $this->plannen = $plannen;

        return $this;
    }

    public function getActiepunten(): ?string
    {
        return $this->actiepunten;
    }

    public function setActiepunten(string $actiepunten): self
    {
        $this->actiepunten = $actiepunten;

        return $this;
    }
}
