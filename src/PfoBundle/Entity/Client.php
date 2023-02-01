<?php

namespace PfoBundle\Entity;

use AppBundle\Entity\Geslacht;
use AppBundle\Model\AddressTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="PfoBundle\Repository\ClientRepository")
 * @ORM\Table(
 *     name="pfo_clienten",
 *     indexes={
 *         @ORM\Index(name="idx_pfo_clienten_roepnaam", columns={"roepnaam"}),
 *         @ORM\Index(name="idx_pfo_clienten_achternaam", columns={"achternaam"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Client
{
    use IdentifiableTrait;
    use NameTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;
    use AddressTrait;

    public const DUBBELE_DIAGNOSE_NEE = 0;
    public const DUBBELE_DIAGNOSE_JA = 1;
    public const DUBBELE_DIAGNOSE_VERMOEDELIJK = 2;
    public const EERDERE_HULPVERLENING_NEE = 0;
    public const EERDERE_HULPVERLENING_JA = 1;

    /**
     * @ORM\Column(name="roepnaam", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $voornaam;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $geboortedatum;

    /**
     * @var Geslacht
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geslacht")
     * @Gedmo\Versioned
     */
    protected $geslacht;

    /**
     * @ORM\Column(type="string", nullable=true, length=50)
     * @Gedmo\Versioned
     */
    protected $postcode;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker", cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @ORM\Column(name="telefoon_mobiel", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    //protected $mobiel;

    /**
     * @var Groep
     *
     * @ORM\ManyToOne(targetEntity="Groep")
     * @ORM\JoinColumn(name="groep")
     * @Gedmo\Versioned
     */
    private $groep;

    /**
     * @var AardRelatie
     *
     * @ORM\ManyToOne(targetEntity="AardRelatie")
     * @ORM\JoinColumn(name="aard_relatie")
     * @Gedmo\Versioned
     */
    private $aardRelatie;

    /**
     * @var bool
     *
     * @ORM\Column(name="dubbele_diagnose", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $dubbeleDiagnose;

    /**
     * @var bool
     *
     * @ORM\Column(name="eerdere_hulpverlening", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $eerdereHulpverlening;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $notitie;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $via;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $hulpverleners;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $contacten;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begeleidings_formulier", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $begeleidingsformulierOverhandigd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="brief_huisarts", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $briefHuisartsVerstuurd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="evaluatie_formulier", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $evaluatieformulierOverhandigd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_afgesloten", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", mappedBy="clienten", cascade={"persist"})
     * @ORM\OrderBy({"created": "DESC"})
     */
    private $verslagen;

    /**
     * @var Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="pfo_clienten_documenten",
     *     inverseJoinColumns={@ORM\JoinColumn(unique=true, onDelete="CASCADE")}
     * )
     * @ORM\OrderBy({"created": "DESC"})
     */
    private $documenten;

    /**
     * @var Client[]
     *
     * @ORM\ManyToMany(targetEntity="Client", inversedBy="gekoppeldeClienten", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="pfo_clienten_supportgroups",
     *     joinColumns={@ORM\JoinColumn(name="pfo_supportgroup_client_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="pfo_client_id")}
     * )
     */
    private $hoofdclienten;

    /**
     * @var Client[]
     *
     * @ORM\ManyToMany(targetEntity="Client", mappedBy="hoofdclienten", cascade={"persist"})
     */
    private $gekoppeldeClienten;

    public function __construct()
    {
        $this->verslagen = new ArrayCollection();
        $this->documenten = new ArrayCollection();
        $this->hoofdclienten = new ArrayCollection();
        $this->gekoppeldeClienten = new ArrayCollection();
    }

    /**
     * @return Groep
     */
    public function getGroep()
    {
        return $this->groep;
    }

    /**
     * @param Groep $groep
     */
    public function setGroep(Groep $groep)
    {
        $this->groep = $groep;

        return $this;
    }

    /**
     * @return AardRelatie
     */
    public function getAardRelatie()
    {
        return $this->aardRelatie;
    }

    /**
     * @param AardRelatie $aardRelatie
     */
    public function setAardRelatie(AardRelatie $aardRelatie)
    {
        $this->aardRelatie = $aardRelatie;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDubbeleDiagnose()
    {
        return $this->dubbeleDiagnose;
    }

    /**
     * @param bool $dubbeleDiagnose
     */
    public function setDubbeleDiagnose($dubbeleDiagnose)
    {
        $this->dubbeleDiagnose = $dubbeleDiagnose;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEerdereHulpverlening()
    {
        return $this->eerdereHulpverlening;
    }

    /**
     * @param bool $eerdereHulpverlening
     */
    public function setEerdereHulpverlening($eerdereHulpverlening)
    {
        $this->eerdereHulpverlening = $eerdereHulpverlening;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotitie()
    {
        return $this->notitie;
    }

    /**
     * @param string $notitie
     */
    public function setNotitie($notitie)
    {
        $this->notitie = $notitie;

        return $this;
    }

    /**
     * @return string
     */
    public function getVia()
    {
        return $this->via;
    }

    /**
     * @param string $via
     */
    public function setVia($via)
    {
        $this->via = $via;

        return $this;
    }

    /**
     * @return string
     */
    public function getHulpverleners()
    {
        return $this->hulpverleners;
    }

    /**
     * @param string $hulpverleners
     */
    public function setHulpverleners($hulpverleners)
    {
        $this->hulpverleners = $hulpverleners;

        return $this;
    }

    /**
     * @return string
     */
    public function getContacten()
    {
        return $this->contacten;
    }

    /**
     * @param string $contacten
     */
    public function setContacten($contacten)
    {
        $this->contacten = $contacten;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBegeleidingsformulierOverhandigd()
    {
        return $this->begeleidingsformulierOverhandigd;
    }

    /**
     * @param \DateTime $begeleidingsformulierOverhandigd
     */
    public function setBegeleidingsformulierOverhandigd(\DateTime $begeleidingsformulierOverhandigd = null)
    {
        $this->begeleidingsformulierOverhandigd = $begeleidingsformulierOverhandigd;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBriefHuisartsVerstuurd()
    {
        return $this->briefHuisartsVerstuurd;
    }

    /**
     * @param \DateTime $briefHuisartsVerstuurd
     */
    public function setBriefHuisartsVerstuurd(\DateTime $briefHuisartsVerstuurd = null)
    {
        $this->briefHuisartsVerstuurd = $briefHuisartsVerstuurd;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEvaluatieformulierOverhandigd()
    {
        return $this->evaluatieformulierOverhandigd;
    }

    /**
     * @param \DateTime $evaluatieformulierOverhandigd
     */
    public function setEvaluatieformulierOverhandigd(\DateTime $evaluatieformulierOverhandigd = null)
    {
        $this->evaluatieformulierOverhandigd = $evaluatieformulierOverhandigd;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    /**
     * @param \DateTime $afsluitdatum
     */
    public function setAfsluitdatum(\DateTime $afsluitdatum = null)
    {
        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }

    public function getGeboortedatum()
    {
        return $this->geboortedatum;
    }

    public function setGeboortedatum(\DateTime $geboortedatum = null)
    {
        $this->geboortedatum = $geboortedatum;

        return $this;
    }

    public function getGeslacht()
    {
        return $this->geslacht;
    }

    public function setGeslacht(Geslacht $geslacht = null)
    {
        $this->geslacht = $geslacht;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    public function getAdres()
    {
        return $this->adres;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getPlaats()
    {
        return $this->plaats;
    }

    public function getMobiel()
    {
        return $this->mobiel;
    }

    public function getTelefoon()
    {
        return $this->telefoon;
    }

    public function setAdres($adres)
    {
        $this->adres = $adres;

        return $this;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function setPlaats($plaats)
    {
        $this->plaats = $plaats;

        return $this;
    }

    public function setMobiel($mobiel)
    {
        $this->mobiel = $mobiel;

        return $this;
    }

    public function setTelefoon($telefoon)
    {
        $this->telefoon = $telefoon;

        return $this;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;
        $verslag->addClient($this);

        return $this;
    }

    /**
     * @return Document[]
     */
    public function getDocumenten()
    {
        return $this->documenten;
    }

    /**
     * @param Document $document
     *
     * @return self
     */
    public function addDocument(Document $document)
    {
        $this->documenten[] = $document;

        return $this;
    }

    /**
     * @param Document $document
     *
     * @return self
     */
    public function removeDocument(Document $document)
    {
        $this->documenten->removeElement($document);

        return $this;
    }

    public function isGekoppeld()
    {
        return count((array) $this->hoofdclienten) > 0 || count((array) $this->gekoppeldeClienten) > 0;
    }

    public function getHoofdclient()
    {
        if (count((array) $this->hoofdclienten) > 0) {
            return $this->hoofdclienten[0];
        }
    }

    public function setHoofdclient(self $client = null)
    {
        $this->hoofdclienten = new ArrayCollection([]);

        if ($client) {
            $this->hoofdclienten[] = $client;
        }

        return $this;
    }

    public function getGekoppeldeClienten()
    {
        return $this->gekoppeldeClienten;
    }

    public function setGekoppeldeClienten($clienten)
    {
        $this->gekoppeldeClienten = $clienten;
        foreach ($clienten as $client) {
            $client->setHoofdclient($this);
        }

        return $this;
    }
}
