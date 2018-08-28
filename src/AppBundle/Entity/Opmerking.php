<?php

namespace AppBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="opmerkingen",
 *     indexes={
 *         @ORM\Index(name="idx_opmerkingen_klant_id", columns={"klant_id"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Opmerking
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="opmerkingen")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column()
     * @Gedmo\Versioned
     */
    private $beschrijving;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $gezien;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \AppBundle\Entity\Klant
     */
    public function getKlant()
    {
        return $this->klant;
    }

    /**
     * @param \AppBundle\Entity\Klant $klant
     */
    public function setKlant($klant)
    {
        $this->klant = $klant;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param \AppBundle\Entity\Categorie $categorie
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
     * @return boolean
     */
    public function isGezien()
    {
        return $this->gezien;
    }

    /**
     * @param boolean $gezien
     */
    public function setGezien($gezien)
    {
        $this->gezien = $gezien;

        return $this;
    }
}
