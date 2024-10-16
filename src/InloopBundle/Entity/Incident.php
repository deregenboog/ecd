<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="inloop_incidenten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Incident
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(name="datum", type="date")
     *
     * @Assert\NotNull
     */
    private $datum;

    /**
     * @ORM\Column(name="remark", type="text", nullable=true)
     */
    private $opmerking;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $politie = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $ambulance = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $crisisdienst = false;

    /**
     * @ORM\ManyToOne(targetEntity="Locatie")
     */
    private $locatie;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="incidenten", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull
     */
    private Klant $klant;

    public function __construct(?Klant $klant = null)
    {
        if (null !== $klant) {
            $this->setKlant($klant);
        }

        $this->setDatum(new \DateTime());
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getDatum()->format('d-m-Y');
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

    /**
     * @return mixed
     */
    public function getDatum(): ?\DateTime
    {
        return $this->datum;
    }

    /**
     * @return Incident
     */
    public function setDatum($datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getOpmerking()
    {
        return utf8_decode($this->opmerking);
    }

    /**
     * @return Incident
     */
    public function setOpmerking($opmerking)
    {
        $this->opmerking = utf8_encode($opmerking);

        return $this;
    }

    public function isPolitie(): bool
    {
        return $this->politie;
    }

    public function setPolitie(bool $politie): Incident
    {
        $this->politie = $politie;

        return $this;
    }

    public function isAmbulance(): bool
    {
        return $this->ambulance;
    }

    public function setAmbulance(bool $ambulance): Incident
    {
        $this->ambulance = $ambulance;

        return $this;
    }

    public function isCrisisdienst(): bool
    {
        return $this->crisisdienst;
    }

    public function setCrisisdienst(bool $crisisdienst): Incident
    {
        $this->crisisdienst = $crisisdienst;

        return $this;
    }

    public function getLocatie()
    {
        return $this->locatie;
    }

    /**
     * @return Incident
     */
    public function setLocatie($locatie)
    {
        $this->locatie = $locatie;

        return $this;
    }
}
