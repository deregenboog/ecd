<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\IzKlantRepository")
 * @Gedmo\Loggable
 */
class IzKlant extends IzDeelnemer
{
    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(name="foreign_key", nullable=true)
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @var ArrayCollection|Hulpvraag[]
     * @ORM\OneToMany(targetEntity="Hulpvraag", mappedBy="izKlant", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC"})
     */
    private $hulpvragen;

    /**
     * @var ContactOntstaan
     * @ORM\ManyToOne(targetEntity="ContactOntstaan")
     * @ORM\JoinColumn(name="contact_ontstaan")
     * @Gedmo\Versioned
     */
    private $contactOntstaan;

    /**
     * @var string
     *
     * @ORM\Column(name="organisatie")
     * @Gedmo\Versioned
     */
    private $organisatieAanmelder;

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

    public function __construct(Klant $klant = null)
    {
        $this->klant = $klant;
        $this->datumAanmelding = new \DateTime('today');
        $this->hulpvragen = new ArrayCollection();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->klant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
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

    public function getHulpvragen()
    {
        return $this->hulpvragen;
    }

    public function getKoppelingen()
    {
        return new ArrayCollection(array_map(
            function (Hulpvraag $hulpvraag) {
                return $hulpvraag->getKoppeling();
            },
            $this->getGekoppeldeHulpvragen()->toArray()
        ));
    }

    public function addHulpvraag(Hulpvraag $hulpvraag)
    {
        $this->hulpvragen[] = $hulpvraag;
        $hulpvraag->setIzKlant($this);

        return $this;
    }

    public function getNietGekoppeldeHulpvragen()
    {
        return new ArrayCollection(array_filter(
            $this->hulpvragen->toArray(),
            function (Hulpvraag $hulpvraag) {
                return !$hulpvraag->isGekoppeld();
            }
        ));
    }

    public function getGekoppeldeHulpvragen()
    {
        return new ArrayCollection(array_filter(
            $this->hulpvragen->toArray(),
            function (Hulpvraag $hulpvraag) {
                return $hulpvraag->isGekoppeld();
            }
        ));
    }

    public function getOpenHulpvragen()
    {
        return new ArrayCollection(array_filter(
            $this->getNietGekoppeldeHulpvragen()->toArray(),
            function (Hulpvraag $hulpvraag) {
                return !$hulpvraag->isAfgesloten();
            }
        ));
    }

    public function hasOpenHulpvragen()
    {
        return count($this->getOpenHulpvragen()) > 0;
    }

    public function getAfgeslotenHulpvragen()
    {
        return new ArrayCollection(array_filter(
            $this->getNietGekoppeldeHulpvragen()->toArray(),
            function (Hulpvraag $hulpvraag) {
                return $hulpvraag->isAfgesloten();
            }
        ));
    }

    public function getContactOntstaan()
    {
        return $this->contactOntstaan;
    }

    public function setContactOntstaan(ContactOntstaan $contactOntstaan = null)
    {
        $this->contactOntstaan = $contactOntstaan;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrganisatieAanmelder()
    {
        return $this->organisatieAanmelder;
    }

    /**
     * @param string $organisatieAanmelder
     */
    public function setOrganisatieAanmelder($organisatieAanmelder)
    {
        $this->organisatieAanmelder = $organisatieAanmelder;

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

    public function isCloseable()
    {
        return 0 === count($this->getOpenHulpvragen()) + count($this->getActieveKoppelingen());
    }
}
