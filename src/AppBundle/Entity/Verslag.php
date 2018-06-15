<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use InloopBundle\Entity\Locatie;

/**
 * @ORM\Entity
 * @ORM\Table(name="verslagen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verslag
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="verslagen")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     * @Gedmo\Versioned
     */
    private $locatie;

    /**
     * @ORM\ManyToOne(targetEntity="Medewerker")
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var Contactsoort
     *
     * @ORM\ManyToOne(targetEntity="Contactsoort")
     * @Gedmo\Versioned
     */
    private $contactsoort;

    /**
     * @var int
     *
     * @ORM\Column(name="aanpassing_verslag", type="integer")
     * @Gedmo\Versioned
     */
    private $duur;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * @param \DateTime $datum
     */
    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    /**
     * @return string
     */
    public function getOpmerking()
    {
        return $this->opmerking;
    }

    /**
     * @param string $opmerking
     */
    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;

        return $this;
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
    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    /**
     * @return Locatie
     */
    public function getLocatie()
    {
        return $this->locatie;
    }

    /**
     * @param Locatie $locatie
     */
    public function setLocatie(Locatie $locatie)
    {
        $this->locatie = $locatie;

        return $this;
    }

    /**
     * @return Medewerker
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    /**
     * @param Medewerker $medewerker
     */
    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * @return Contactsoort
     */
    public function getContactsoort()
    {
        return $this->contactsoort;
    }

    /**
     * @param Contactsoort $contactsoort
     * @return \AppBundle\Entity\Verslag
     */
    public function setContactsoort(Contactsoort $contactsoort)
    {
        $this->contactsoort = $contactsoort;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuur()
    {
        return $this->duur;
    }

    /**
     * @param int $duur
     * @return \AppBundle\Entity\Verslag
     */
    public function setDuur($duur)
    {
        $this->duur = $duur;

        return $this;
    }
}
