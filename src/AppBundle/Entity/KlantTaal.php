<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="klant_taal",
 *     uniqueConstraints={
 *
 *         @ORM\UniqueConstraint(columns={"klant_id", "taal_id"})
 *     }
 * )
 *
 * @Gedmo\Loggable
 */
class KlantTaal
{
    use IdentifiableTrait;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="klantTalen")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Taal
     *
     * @ORM\ManyToOne(targetEntity="Taal")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $taal;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $voorkeur = false;

    public function __construct(Klant $klant, Taal $taal)
    {
        $this->klant = $klant;
        $this->taal = $taal;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getTaal()
    {
        return $this->taal;
    }

    public function setTaal(Taal $taal)
    {
        $this->taal = $taal;

        return $this;
    }

    public function isVoorkeur(): bool
    {
        return $this->voorkeur;
    }

    public function setVoorkeur(bool $voorkeur)
    {
        $this->voorkeur = $voorkeur;

        return $this;
    }
}
