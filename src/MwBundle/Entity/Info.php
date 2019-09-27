<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="verslaginfos")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Info
{
    const INSTANTIES = [
        0 => 'DWI',
        1 => 'UWV',
        2 => 'Anders',
    ];

    use IdentifiableTrait;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(unique=true, nullable=false)
     * @Gedmo\Versioned
     * @Assert\NotNull
     */
    private $klant;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $advocaat;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $casemanager;

    /**
     * @ORM\Column(name="casemanager_email", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Email
     */
    private $casemanagerEmail;

    /**
     * @ORM\Column(name="casemanager_telefoonnummer", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $casemanagerTelefoon;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $trajectbegeleider;

    /**
     * @ORM\Column(name="trajectbegeleider_email", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Email
     */
    private $trajectbegeleiderEmail;

    /**
     * @ORM\Column(name="trajectbegeleider_telefoonnummer", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $trajectbegeleiderTelefoon;

    /**
     * @ORM\Column(name="trajecthouder_extern_organisatie", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $trajecthouderExternOrganisatie;

    /**
     * @ORM\Column(name="trajecthouder_extern_naam", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $trajecthouderExternNaam;

    /**
     * @ORM\Column(name="trajecthouder_extern_email", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Email
     */
    private $trajecthouderExternEmail;

    /**
     * @ORM\Column(name="trajecthouder_extern_telefoonnummer", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $trajecthouderExternTelefoon;

    /**
     * @ORM\Column(name="overige_contactpersonen_extern", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $overigeContactpersonenExtern;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $instantie;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $registratienummer;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $budgettering;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $contactpersoon;

    /**
     * @ORM\Column(name="klantmanager_naam", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $klantmanagerNaam;

    /**
     * @ORM\Column(name="klantmanager_email", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Email
     */
    private $klantmanagerEmail;

    /**
     * @ORM\Column(name="klantmanager_telefoonnummer", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $klantmanagerTelefoon;

    /**
     * @ORM\Column(name="sociaal_netwerk", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $sociaalNetwerk;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $bankrekeningnummer;

    /**
     * @ORM\Column(name="polisnummer_ziektekostenverzekering", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $polisnummerZiektekostenverzekering;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $inschrijfnummer;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $wachtwoord;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $telefoonnummer;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $adres;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $overigen;

    public function __construct(Klant $klant)
    {
        $this->klant = $klant;
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
     * @return mixed
     */
    public function getAdvocaat()
    {
        return $this->advocaat;
    }

    /**
     * @param mixed $advocaat
     */
    public function setAdvocaat($advocaat)
    {
        $this->advocaat = $advocaat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCasemanager()
    {
        return $this->casemanager;
    }

    /**
     * @param mixed $casemanager
     */
    public function setCasemanager($casemanager)
    {
        $this->casemanager = $casemanager;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCasemanagerEmail()
    {
        return $this->casemanagerEmail;
    }

    /**
     * @param mixed $casemanagerEmail
     */
    public function setCasemanagerEmail($casemanagerEmail)
    {
        $this->casemanagerEmail = $casemanagerEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCasemanagerTelefoon()
    {
        return $this->casemanagerTelefoon;
    }

    /**
     * @param mixed $casemanagerTelefoon
     */
    public function setCasemanagerTelefoon($casemanagerTelefoon)
    {
        $this->casemanagerTelefoon = $casemanagerTelefoon;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrajectbegeleider()
    {
        return $this->trajectbegeleider;
    }

    /**
     * @param mixed $trajectbegeleider
     */
    public function setTrajectbegeleider($trajectbegeleider)
    {
        $this->trajectbegeleider = $trajectbegeleider;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrajectbegeleiderEmail()
    {
        return $this->trajectbegeleiderEmail;
    }

    /**
     * @param mixed $trajectbegeleiderEmail
     */
    public function setTrajectbegeleiderEmail($trajectbegeleiderEmail)
    {
        $this->trajectbegeleiderEmail = $trajectbegeleiderEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrajectbegeleiderTelefoon()
    {
        return $this->trajectbegeleiderTelefoon;
    }

    /**
     * @param mixed $trajectbegeleiderTelefoon
     */
    public function setTrajectbegeleiderTelefoon($trajectbegeleiderTelefoon)
    {
        $this->trajectbegeleiderTelefoon = $trajectbegeleiderTelefoon;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrajecthouderExternOrganisatie()
    {
        return $this->trajecthouderExternOrganisatie;
    }

    /**
     * @param mixed $trajecthouderExternOrganisatie
     */
    public function setTrajecthouderExternOrganisatie($trajecthouderExternOrganisatie)
    {
        $this->trajecthouderExternOrganisatie = $trajecthouderExternOrganisatie;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrajecthouderExternNaam()
    {
        return $this->trajecthouderExternNaam;
    }

    /**
     * @param mixed $trajecthouderExternNaam
     */
    public function setTrajecthouderExternNaam($trajecthouderExternNaam)
    {
        $this->trajecthouderExternNaam = $trajecthouderExternNaam;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrajecthouderExternEmail()
    {
        return $this->trajecthouderExternEmail;
    }

    /**
     * @param mixed $trajecthouderExternEmail
     */
    public function setTrajecthouderExternEmail($trajecthouderExternEmail)
    {
        $this->trajecthouderExternEmail = $trajecthouderExternEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrajecthouderExternTelefoon()
    {
        return $this->trajecthouderExternTelefoon;
    }

    /**
     * @param mixed $trajecthouderExternTelefoon
     */
    public function setTrajecthouderExternTelefoon($trajecthouderExternTelefoon)
    {
        $this->trajecthouderExternTelefoon = $trajecthouderExternTelefoon;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOverigeContactpersonenExtern()
    {
        return $this->overigeContactpersonenExtern;
    }

    /**
     * @param mixed $overigeContactpersonenExtern
     */
    public function setOverigeContactpersonenExtern($overigeContactpersonenExtern)
    {
        $this->overigeContactpersonenExtern = $overigeContactpersonenExtern;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstantie()
    {
        return $this->instantie;
    }

    /**
     * @param mixed $instantie
     */
    public function setInstantie($instantie)
    {
        $this->instantie = $instantie;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegistratienummer()
    {
        return $this->registratienummer;
    }

    /**
     * @param mixed $registratienummer
     */
    public function setRegistratienummer($registratienummer)
    {
        $this->registratienummer = $registratienummer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBudgettering()
    {
        return $this->budgettering;
    }

    /**
     * @param mixed $budgettering
     */
    public function setBudgettering($budgettering)
    {
        $this->budgettering = $budgettering;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContactpersoon()
    {
        return $this->contactpersoon;
    }

    /**
     * @param mixed $contactpersoon
     */
    public function setContactpersoon($contactpersoon)
    {
        $this->contactpersoon = $contactpersoon;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKlantmanagerNaam()
    {
        return $this->klantmanagerNaam;
    }

    /**
     * @param mixed $klantmanagerNaam
     */
    public function setKlantmanagerNaam($klantmanagerNaam)
    {
        $this->klantmanagerNaam = $klantmanagerNaam;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKlantmanagerEmail()
    {
        return $this->klantmanagerEmail;
    }

    /**
     * @param mixed $klantmanagerEmail
     */
    public function setKlantmanagerEmail($klantmanagerEmail)
    {
        $this->klantmanagerEmail = $klantmanagerEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKlantmanagerTelefoon()
    {
        return $this->klantmanagerTelefoon;
    }

    /**
     * @param mixed $klantmanagerTelefoon
     */
    public function setKlantmanagerTelefoon($klantmanagerTelefoon)
    {
        $this->klantmanagerTelefoon = $klantmanagerTelefoon;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSociaalNetwerk()
    {
        return $this->sociaalNetwerk;
    }

    /**
     * @param mixed $sociaalNetwerk
     */
    public function setSociaalNetwerk($sociaalNetwerk)
    {
        $this->sociaalNetwerk = $sociaalNetwerk;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankrekeningnummer()
    {
        return $this->bankrekeningnummer;
    }

    /**
     * @param mixed $bankrekeningnummer
     */
    public function setBankrekeningnummer($bankrekeningnummer)
    {
        $this->bankrekeningnummer = $bankrekeningnummer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPolisnummerZiektekostenverzekering()
    {
        return $this->polisnummerZiektekostenverzekering;
    }

    /**
     * @param mixed $polisnummerZiektekostenverzekering
     */
    public function setPolisnummerZiektekostenverzekering($polisnummerZiektekostenverzekering)
    {
        $this->polisnummerZiektekostenverzekering = $polisnummerZiektekostenverzekering;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInschrijfnummer()
    {
        return $this->inschrijfnummer;
    }

    /**
     * @param mixed $inschrijfnummer
     */
    public function setInschrijfnummer($inschrijfnummer)
    {
        $this->inschrijfnummer = $inschrijfnummer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWachtwoord()
    {
        return $this->wachtwoord;
    }

    /**
     * @param mixed $wachtwoord
     */
    public function setWachtwoord($wachtwoord)
    {
        $this->wachtwoord = $wachtwoord;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTelefoonnummer()
    {
        return $this->telefoonnummer;
    }

    /**
     * @param mixed $telefoonnummer
     */
    public function setTelefoonnummer($telefoonnummer)
    {
        $this->telefoonnummer = $telefoonnummer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdres()
    {
        return $this->adres;
    }

    /**
     * @param mixed $adres
     */
    public function setAdres($adres)
    {
        $this->adres = $adres;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOverigen()
    {
        return $this->overigen;
    }

    /**
     * @param mixed $overigen
     */
    public function setOverigen($overigen)
    {
        $this->overigen = $overigen;

        return $this;
    }
}
