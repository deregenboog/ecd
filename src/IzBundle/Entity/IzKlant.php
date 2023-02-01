<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
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
     * @ORM\JoinColumn(name="foreign_key")
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @var ArrayCollection|Hulpvraag[]
     * @ORM\OneToMany(targetEntity="Hulpvraag", mappedBy="izKlant", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC", "koppelingStartdatum" = "DESC"})
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
     * @ORM\Column(name="organisatie", length=100, nullable=true)
     * @Gedmo\Versioned
     */
    private $organisatieAanmelder;

    /**
     * @var string
     *
     * @ORM\Column(name="naam_aanmelder", length=100, nullable=true)
     * @Gedmo\Versioned
     */
    private $naamAanmelder;

    /**
     * @var string
     *
     * @ORM\Column(name="email_aanmelder", length=100, nullable=true)
     * @Gedmo\Versioned
     */
    private $emailAanmelder;

    /**
     * @var string
     *
     * @ORM\Column(name="telefoon_aanmelder", length=100, nullable=true)
     * @Gedmo\Versioned
     */
    private $telefoonAanmelder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

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

    public function addHulpvraag(Hulpvraag $hulpvraag)
    {
        $this->hulpvragen[] = $hulpvraag;
        $hulpvraag->setIzKlant($this);

        return $this;
    }

    public function getNietGekoppeldeHulpvragen()
    {
        $criteria = Criteria::create()->where(Criteria::expr()->isNull('hulpaanbod'));

        return $this->hulpvragen->matching($criteria);
    }

    public function getGekoppeldeHulpvragen()
    {
        $criteria = Criteria::create()->where(Criteria::expr()->neq('hulpaanbod', null));

        return $this->hulpvragen->matching($criteria);
    }

    public function getOpenHulpvragen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->orX(
                Criteria::expr()->isNull('einddatum'),
                Criteria::expr()->gt('einddatum', new \DateTime('today'))
            ))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->getNietGekoppeldeHulpvragen()->matching($criteria);
    }

    public function getActieveKoppelingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->orX(
                Criteria::expr()->isNull('koppelingEinddatum'),
                Criteria::expr()->gt('koppelingEinddatum', new \DateTime('today'))
            ))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->getGekoppeldeHulpvragen()->matching($criteria);
    }

    public function getAfgeslotenHulpvragen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->andX(
                Criteria::expr()->neq('einddatum', null),
                Criteria::expr()->lte('einddatum', new \DateTime('today'))
            ))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->getNietGekoppeldeHulpvragen()->matching($criteria);
    }

    public function getAfgeslotenKoppelingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->andX(
                Criteria::expr()->neq('koppelingEinddatum', null),
                Criteria::expr()->lte('koppelingEinddatum', new \DateTime('today'))
            ))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->getGekoppeldeHulpvragen()->matching($criteria);
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

//        return !$this->isAfgesloten() &&
//            0 === count($this->getOpenHulpvragen()) + count($this->getActieveKoppelingen());

        /**
         * in theorie zou hierboven een < 0 waarde uit kunnen komen als de koppelingen en hulpvragen telling niet klopt,
         * waardoor ie raar gedrag vertoont...dus onderstaande even uitgeschreven.
         */
        $oh = is_array($this->getOpenHulpvragen()) || $this->getOpenHulpvragen() instanceof \Countable ? count($this->getOpenHulpvragen()) : 0;
        $ak = is_array($this->getActieveKoppelingen()) || $this->getActieveKoppelingen() instanceof \Countable ? count($this->getActieveKoppelingen()) : 0;
        $afgesloten = $this->isAfgesloten();
        $oh = ($oh < 0) ? 0 : $oh;
        $ak = ($ak < 0) ? 0 : $ak;

        return ($afgesloten === false && $oh+$ak === 0);
    }
}
