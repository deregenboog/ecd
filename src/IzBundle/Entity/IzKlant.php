<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;
use Doctrine\Common\Collections\Criteria;

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
     * @var MatchingKlant
     * @ORM\OneToOne(targetEntity="MatchingKlant", mappedBy="izKlant", cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $matching;

    /**
     * @var ArrayCollection|IzHulpvraag[]
     * @ORM\OneToMany(targetEntity="IzHulpvraag", mappedBy="izKlant", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC", "koppelingStartdatum" = "DESC"})
     */
    private $izHulpvragen;

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

    public function addHulpvraag(IzHulpvraag $hulpvraag)
    {
        $this->izHulpvragen[] = $hulpvraag;
        $hulpvraag->setIzKlant($this);

        return $this;
    }

    public function getOpenHulpvragen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('izHulpaanbod'))
            ->andWhere(Criteria::expr()->isNull('einddatum'))
        ;

        return $this->izHulpvragen->matching($criteria);
    }

    public function getActieveKoppelingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->neq('izHulpaanbod', null))
            ->andWhere(Criteria::expr()->orX(
                Criteria::expr()->isNull('koppelingEinddatum'),
                Criteria::expr()->gte('koppelingEinddatum', new \DateTime('today'))
            ))
        ;

        return $this->izHulpvragen->matching($criteria);
    }

    public function getAfgeslotenHulpvragen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('izHulpaanbod'))
            ->andWhere(Criteria::expr()->neq('einddatum', null))
        ;

        return $this->izHulpvragen->matching($criteria);
    }

    public function getAfgeslotenKoppelingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->neq('izHulpaanbod', null))
            ->andWhere(Criteria::expr()->lt('koppelingEinddatum', new \DateTime('today')))
        ;

        return $this->izHulpvragen->matching($criteria);
    }

    public function getOntstaanContact()
    {
        return $this->ontstaanContact;
    }

    public function setOntstaanContact(ContactOntstaan $ontstaanContact)
    {
        $this->ontstaanContact = $ontstaanContact;

        return $this;
    }

    public function getMatching()
    {
        return $this->matching;
    }

    public function setMatching(MatchingKlant $matching)
    {
        $this->matching = $matching;
        $matching->setIzKlant($this);

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
}
