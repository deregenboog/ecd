<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\IzKlantRepository")
 * @Gedmo\Loggable
 */
class IzKlant extends IzDeelnemer
{
    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(name="foreign_key", nullable=true)
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @var MatchingKlant
     * @ORM\OneToOne(targetEntity="MatchingKlant", mappedBy="izKlant")
     * @Gedmo\Versioned
     */
    protected $matching;

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
     * @Gedmo\Versioned
     */
    protected $izOntstaanContact;

    /**
     * @var string
     *
     * @ORM\Column()
     * @Gedmo\Versioned
     */
    private $organisatie;

    /**
     * @var string
     *
     * @ORM\Column(name="naam_aanmelder")
     * @Gedmo\Versioned
     */
    private $naamAanmelder;

    /**
     * @var string
     *
     * @ORM\Column(name="email_aanmelder")
     * @Gedmo\Versioned
     */
    private $emailAanmelder;

    /**
     * @var string
     *
     * @ORM\Column(name="telefoon_aanmelder")
     * @Gedmo\Versioned
     */
    private $telefoonAanmelder;

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

    public function getMatching()
    {
        return $this->matching;
    }

    /**
     * @return string
     */
    public function getOrganisatie()
    {
        return $this->organisatie;
    }

    /**
     * @param string $organisatie
     */
    public function setOrganisatie($organisatie)
    {
        $this->organisatie = $organisatie;

        return $this;
    }

    /**
     * @return string
     */
    public function getNaamAanmelder()
    {
        return $this->naamAanmelder;
    }

    /**
     * @param string $naamAanmelder
     */
    public function setNaamAanmelder($naamAanmelder)
    {
        $this->naamAanmelder = $naamAanmelder;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAanmelder()
    {
        return $this->emailAanmelder;
    }

    /**
     * @param string $emailAanmelder
     */
    public function setEmailAanmelder($emailAanmelder)
    {
        $this->emailAanmelder = $emailAanmelder;

        return $this;
    }

    /**
     * @return string
     */
    public function getTelefoonAanmelder()
    {
        return $this->telefoonAanmelder;
    }

    /**
     * @param string $telefoonAanmelder
     */
    public function setTelefoonAanmelder($telefoonAanmelder)
    {
        $this->telefoonAanmelder = $telefoonAanmelder;

        return $this;
    }
}
