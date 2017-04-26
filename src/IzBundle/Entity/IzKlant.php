<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\IzKlantRepository")
 */
class IzKlant extends IzDeelnemer
{
    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     */
    protected $klant;

    /**
     * @var ArrayCollection|IzHulpvraag[]
     * @ORM\OneToMany(targetEntity="IzHulpvraag", mappedBy="izKlant")
     * @ORM\OrderBy({"startdatum" = "DESC", "koppelingStartdatum" = "DESC"})
     */
    private $izHulpvragen;

    /**
     * @var IzOntstaanContact
     * @ORM\ManyToOne(targetEntity="IzOntstaanContact")
     * @ORM\JoinColumn(name="contact_ontstaan")
     */
    protected $izOntstaanContact;

    public function __construct()
    {
        $this->izHulpvragen = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->klant;
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

    public function getIzHulpvragen()
    {
        return $this->izHulpvragen;
    }

    public function getIzOntstaanContact()
    {
        return $this->izOntstaanContact;
    }

    public function setIzOntstaanContact(IzOntstaanContact $izOntstaanContact)
    {
        $this->izOntstaanContact = $izOntstaanContact;

        return $this;
    }
}
