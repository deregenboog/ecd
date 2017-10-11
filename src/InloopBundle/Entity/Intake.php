<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="intakes")
 * @Gedmo\Loggable
 */
class Intake
{
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="intakes")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     * @ORM\JoinColumn(name="locatie2_id")
     * @Gedmo\Versioned
     */
    private $intakelocatie;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     * @ORM\JoinColumn(name="locatie1_id")
     * @Gedmo\Versioned
     */
    private $gebruikersruimte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_intake", type="date")
     * @Gedmo\Versioned
     */
    private $intakedatum;

    private $verblijfsstatus;

    private $postadres;

    private $postcode;

    private $woonplaats;

    /**
     * @var bool
     *
     * @ORM\Column(name="toegang_inloophuis", type="boolean")
     * @Gedmo\Versioned
     */
    private $toegangInloophuis;

    /**
     * @var bool
     *
     * @ORM\Column(name="mag_gebruiken", type="boolean")
     * @Gedmo\Versioned
     */
    private $magGebruiken;

    public function getId()
    {
        return $this->id;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;
    }

    public function isToegangInloophuis()
    {
        return $this->toegangInloophuis;
    }

    public function setToegangInloophuis($toegangInloophuis)
    {
        $this->toegangInloophuis = $toegangInloophuis;

        return $this;
    }

    public function getIntakedatum()
    {
        return $this->intakedatum;
    }

    public function getIntakelocatie()
    {
        return $this->intakelocatie;
    }

    public function setIntakelocatie(Locatie $locatie)
    {
        $this->intakelocatie = $locatie;
    }

    public function getGebruikersruimte()
    {
        return $this->gebruikersruimte;
    }

    public function setGebruikersruimte(Locatie $locatie)
    {
        $this->gebruikersruimte = $locatie;
    }
}
