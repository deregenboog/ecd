<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="opmerkingen", indexes={
 *
 *     @ORM\Index(name="idx_opmerkingen_klant_id", columns={"klant_id"})
 * })
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Opmerking
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="opmerkingen")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Gedmo\Versioned
     */
    private $beschrijving;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     *
     * @Gedmo\Versioned
     */
    private $gezien = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct(?Klant $klant = null)
    {
        $this->setKlant($klant);
    }

    /**
     * @return Klant
     */
    public function getKlant()
    {
        return $this->klant;
    }

    /**
     * @param Klant $klant
     */
    public function setKlant($klant)
    {
        $this->klant = $klant;

        return $this;
    }

    /**
     * @return Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param Categorie $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return string
     */
    public function getBeschrijving()
    {
        return $this->beschrijving;
    }

    /**
     * @param string $beschrijving
     */
    public function setBeschrijving($beschrijving)
    {
        $this->beschrijving = $beschrijving;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGezien()
    {
        return $this->gezien;
    }

    /**
     * @param bool $gezien
     */
    public function setGezien($gezien)
    {
        $this->gezien = $gezien;

        return $this;
    }
}
