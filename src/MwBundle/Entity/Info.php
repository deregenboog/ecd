<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="mw_dossier_info")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Info
{
    use IdentifiableTrait;
    public const INSTANTIES = [
        0 => 'DWI',
        1 => 'UWV',
        2 => 'Anders',
    ];

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"}, inversedBy="info")
     *
     * @Gedmo\Versioned
     *
     * @Assert\NotNull
     */
    private $klant;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $advocaat;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @Gedmo\Versioned
     */
    private $casemanager;

    /**
     * @ORM\Column(name="casemanager_email", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     *
     * @Assert\Email
     */
    private $casemanagerEmail;

    /**
     * @ORM\Column(name="casemanager_telefoonnummer", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $casemanagerTelefoon;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @Gedmo\Versioned
     */
    private $trajectbegeleider;

    /**
     * @ORM\Column(name="trajectbegeleider_email", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     *
     * @Assert\Email
     */
    private $trajectbegeleiderEmail;

    /**
     * @ORM\Column(name="trajectbegeleider_telefoonnummer", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $trajectbegeleiderTelefoon;

    /**
     * @ORM\Column(name="trajecthouder_extern_organisatie", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $trajecthouderExternOrganisatie;

    /**
     * @ORM\Column(name="trajecthouder_extern_naam", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $trajecthouderExternNaam;

    /**
     * @ORM\Column(name="trajecthouder_extern_email", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     *
     * @Assert\Email
     */
    private $trajecthouderExternEmail;

    /**
     * @ORM\Column(name="trajecthouder_extern_telefoonnummer", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $trajecthouderExternTelefoon;

    /**
     * @ORM\Column(name="overige_contactpersonen_extern", type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $overigeContactpersonenExtern;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $instantie;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $registratienummer;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $budgettering;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $contactpersoon;

    /**
     * @ORM\Column(name="klantmanager_naam", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $klantmanagerNaam;

    /**
     * @ORM\Column(name="klantmanager_email", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     *
     * @Assert\Email
     */
    private $klantmanagerEmail;

    /**
     * @ORM\Column(name="klantmanager_telefoonnummer", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $klantmanagerTelefoon;

    /**
     * @ORM\Column(name="sociaal_netwerk", type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $sociaalNetwerk;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $bankrekeningnummer;

    /**
     * @ORM\Column(name="polisnummer_ziektekostenverzekering", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $polisnummerZiektekostenverzekering;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $inschrijfnummer;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $wachtwoord;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $telefoonnummer;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $adres;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $overigen;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $risDatumTot;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isGezin = 0;

    public function __construct(Klant $klant)
    {
        $this->klant = $klant;
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

    public function getAdvocaat()
    {
        return $this->advocaat;
    }

    public function setAdvocaat($advocaat)
    {
        $this->advocaat = $advocaat;

        return $this;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    public function getCasemanager()
    {
        return $this->casemanager;
    }

    public function setCasemanager($casemanager)
    {
        $this->casemanager = $casemanager;

        return $this;
    }

    public function getCasemanagerEmail()
    {
        return $this->casemanagerEmail;
    }

    public function setCasemanagerEmail($casemanagerEmail)
    {
        $this->casemanagerEmail = $casemanagerEmail;

        return $this;
    }

    public function getCasemanagerTelefoon()
    {
        return $this->casemanagerTelefoon;
    }

    public function setCasemanagerTelefoon($casemanagerTelefoon)
    {
        $this->casemanagerTelefoon = $casemanagerTelefoon;

        return $this;
    }

    public function getTrajectbegeleider()
    {
        return $this->trajectbegeleider;
    }

    public function setTrajectbegeleider($trajectbegeleider)
    {
        $this->trajectbegeleider = $trajectbegeleider;

        return $this;
    }

    public function getTrajectbegeleiderEmail()
    {
        return $this->trajectbegeleiderEmail;
    }

    public function setTrajectbegeleiderEmail($trajectbegeleiderEmail)
    {
        $this->trajectbegeleiderEmail = $trajectbegeleiderEmail;

        return $this;
    }

    public function getTrajectbegeleiderTelefoon()
    {
        return $this->trajectbegeleiderTelefoon;
    }

    public function setTrajectbegeleiderTelefoon($trajectbegeleiderTelefoon)
    {
        $this->trajectbegeleiderTelefoon = $trajectbegeleiderTelefoon;

        return $this;
    }

    public function getTrajecthouderExternOrganisatie()
    {
        return $this->trajecthouderExternOrganisatie;
    }

    public function setTrajecthouderExternOrganisatie($trajecthouderExternOrganisatie)
    {
        $this->trajecthouderExternOrganisatie = $trajecthouderExternOrganisatie;

        return $this;
    }

    public function getTrajecthouderExternNaam()
    {
        return $this->trajecthouderExternNaam;
    }

    public function setTrajecthouderExternNaam($trajecthouderExternNaam)
    {
        $this->trajecthouderExternNaam = $trajecthouderExternNaam;

        return $this;
    }

    public function getTrajecthouderExternEmail()
    {
        return $this->trajecthouderExternEmail;
    }

    public function setTrajecthouderExternEmail($trajecthouderExternEmail)
    {
        $this->trajecthouderExternEmail = $trajecthouderExternEmail;

        return $this;
    }

    public function getTrajecthouderExternTelefoon()
    {
        return $this->trajecthouderExternTelefoon;
    }

    public function setTrajecthouderExternTelefoon($trajecthouderExternTelefoon)
    {
        $this->trajecthouderExternTelefoon = $trajecthouderExternTelefoon;

        return $this;
    }

    public function getOverigeContactpersonenExtern()
    {
        return $this->overigeContactpersonenExtern;
    }

    public function setOverigeContactpersonenExtern($overigeContactpersonenExtern)
    {
        $this->overigeContactpersonenExtern = $overigeContactpersonenExtern;

        return $this;
    }

    public function getInstantie()
    {
        return $this->instantie;
    }

    public function setInstantie($instantie)
    {
        $this->instantie = $instantie;

        return $this;
    }

    public function getRegistratienummer()
    {
        return $this->registratienummer;
    }

    public function setRegistratienummer($registratienummer)
    {
        $this->registratienummer = $registratienummer;

        return $this;
    }

    public function getBudgettering()
    {
        return $this->budgettering;
    }

    public function setBudgettering($budgettering)
    {
        $this->budgettering = $budgettering;

        return $this;
    }

    public function getContactpersoon()
    {
        return $this->contactpersoon;
    }

    public function setContactpersoon($contactpersoon)
    {
        $this->contactpersoon = $contactpersoon;

        return $this;
    }

    public function getKlantmanagerNaam()
    {
        return $this->klantmanagerNaam;
    }

    public function setKlantmanagerNaam($klantmanagerNaam)
    {
        $this->klantmanagerNaam = $klantmanagerNaam;

        return $this;
    }

    public function getKlantmanagerEmail()
    {
        return $this->klantmanagerEmail;
    }

    public function setKlantmanagerEmail($klantmanagerEmail)
    {
        $this->klantmanagerEmail = $klantmanagerEmail;

        return $this;
    }

    public function getKlantmanagerTelefoon()
    {
        return $this->klantmanagerTelefoon;
    }

    public function setKlantmanagerTelefoon($klantmanagerTelefoon)
    {
        $this->klantmanagerTelefoon = $klantmanagerTelefoon;

        return $this;
    }

    public function getSociaalNetwerk()
    {
        return $this->sociaalNetwerk;
    }

    public function setSociaalNetwerk($sociaalNetwerk)
    {
        $this->sociaalNetwerk = $sociaalNetwerk;

        return $this;
    }

    public function getBankrekeningnummer()
    {
        return $this->bankrekeningnummer;
    }

    public function setBankrekeningnummer($bankrekeningnummer)
    {
        $this->bankrekeningnummer = $bankrekeningnummer;

        return $this;
    }

    public function getPolisnummerZiektekostenverzekering()
    {
        return $this->polisnummerZiektekostenverzekering;
    }

    public function setPolisnummerZiektekostenverzekering($polisnummerZiektekostenverzekering)
    {
        $this->polisnummerZiektekostenverzekering = $polisnummerZiektekostenverzekering;

        return $this;
    }

    public function getInschrijfnummer()
    {
        return $this->inschrijfnummer;
    }

    public function setInschrijfnummer($inschrijfnummer)
    {
        $this->inschrijfnummer = $inschrijfnummer;

        return $this;
    }

    public function getWachtwoord()
    {
        return $this->wachtwoord;
    }

    public function setWachtwoord($wachtwoord)
    {
        $this->wachtwoord = $wachtwoord;

        return $this;
    }

    public function getTelefoonnummer()
    {
        return $this->telefoonnummer;
    }

    public function setTelefoonnummer($telefoonnummer)
    {
        $this->telefoonnummer = $telefoonnummer;

        return $this;
    }

    public function getAdres()
    {
        return $this->adres;
    }

    public function setAdres($adres)
    {
        $this->adres = $adres;

        return $this;
    }

    public function getOverigen()
    {
        return $this->overigen;
    }

    public function setOverigen($overigen)
    {
        $this->overigen = $overigen;

        return $this;
    }

    public function getRisDatumTot()
    {
        return $this->risDatumTot;
    }

    public function setRisDatumTot($risDatumTot): void
    {
        $this->risDatumTot = $risDatumTot;
    }

    public function isGezin(): ?bool
    {
        return $this->isGezin;
    }

    public function setIsGezin(bool $isGezin): void
    {
        $this->isGezin = $isGezin;
    }
}
