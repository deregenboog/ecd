<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_intakes_inkomen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeInkomen extends Intake
{
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $inkomsten;

    /**
     * @var string
     * @ORM\Column(name="inkomsten_toelichting", type="text", nullable=true)
     */
    private $inkomstenToelichting;

    /**
     * @var bool
     * @ORM\Column(name="hulp_bij_inkomen", type="boolean")
     */
    private $hulpBijInkomen;

    /**
     * @var string
     * @ORM\Column(name="hulp_bij_inkomen_toelichting", type="text", nullable=true)
     */
    private $hulpBijInkomenToelichting;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $stadspas;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $schuldenproblematiek;

    /**
     * @var string
     * @ORM\Column(name="schuldenproblematiek_toelichting", type="text", nullable=true)
     */
    private $schuldenproblematiekToelichting;

    public function hasInkomsten(): ?bool
    {
        return $this->inkomsten;
    }

    public function setInkomsten(bool $inkomsten): self
    {
        $this->inkomsten = $inkomsten;

        return $this;
    }

    public function getInkomstenToelichting(): ?string
    {
        return $this->inkomstenToelichting;
    }

    public function setInkomstenToelichting(string $inkomstenToelichting): self
    {
        $this->inkomstenToelichting = $inkomstenToelichting;

        return $this;
    }

    public function hasHulpBijInkomen(): ?bool
    {
        return $this->hulpBijInkomen;
    }

    public function setHulpBijInkomen(bool $hulpBijInkomen): self
    {
        $this->hulpBijInkomen = $hulpBijInkomen;

        return $this;
    }

    public function getHulpBijInkomenToelichting(): ?string
    {
        return $this->hulpBijInkomenToelichting;
    }

    public function setHulpBijInkomenToelichting(string $hulpBijInkomenToelichting): self
    {
        $this->hulpBijInkomenToelichting = $hulpBijInkomenToelichting;

        return $this;
    }

    public function hasStadspas(): ?bool
    {
        return $this->stadspas;
    }

    public function setStadspas(bool $stadspas): self
    {
        $this->stadspas = $stadspas;

        return $this;
    }

    public function hasSchuldenproblematiek(): ?bool
    {
        return $this->schuldenproblematiek;
    }

    public function setSchuldenproblematiek(bool $schuldenproblematiek): self
    {
        $this->schuldenproblematiek = $schuldenproblematiek;

        return $this;
    }

    public function getSchuldenproblematiekToelichting(): ?string
    {
        return $this->schuldenproblematiekToelichting;
    }

    public function setSchuldenproblematiekToelichting(string $schuldenproblematiekToelichting): self
    {
        $this->schuldenproblematiekToelichting = $schuldenproblematiekToelichting;

        return $this;
    }
}
