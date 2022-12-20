<?php

namespace OekraineBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="oekraine_incidenten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Incident
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(name="datum", type="date", nullable=false)
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
     * @ORM\Column(type="boolean", nullable=false, options={"DEFAULT 0"})
     */
    private $politie = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"DEFAULT 0"})
     */
    private $ambulance = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"DEFAULT 0"})
     */
    private $crisisdienst = false;

    /**
     * @ORM\ManyToOne(targetEntity="Locatie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $locatie;

    /**
     * @ORM\ManyToOne(targetEntity="Bezoeker", inversedBy="incidenten", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $bezoeker;

    public function __construct(Bezoeker $bezoeker = null)
    {
        $this->setBezoeker($bezoeker);
        $this->setDatum(new \DateTime());
    }

    public function __toString()
    {
        return sprintf('Incident %s (%s, %s)', $this->bezoeker, $this->datum->format('d-m-Y'), $this->locatie);
    }

    public function getBezoeker()
    {
        return $this->bezoeker;
    }

    public function setBezoeker($bezoeker)
    {
        $this->bezoeker = $bezoeker;

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
     * @param mixed $datum
     * @return Incident
     */
    public function setDatum($datum)
    {
        $this->datum = $datum;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getOpmerking()
    {
        return $this->opmerking;
    }

    /**
     * @param mixed $opmerking
     * @return Incident
     */
    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPolitie(): bool
    {
        return $this->politie;
    }

    /**
     * @param bool $politie
     * @return Incident
     */
    public function setPolitie(bool $politie): Incident
    {
        $this->politie = $politie;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAmbulance(): bool
    {
        return $this->ambulance;
    }

    /**
     * @param bool $ambulance
     * @return Incident
     */
    public function setAmbulance(bool $ambulance): Incident
    {
        $this->ambulance = $ambulance;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCrisisdienst(): bool
    {
        return $this->crisisdienst;
    }

    /**
     * @param bool $crisisdienst
     * @return Incident
     */
    public function setCrisisdienst(bool $crisisdienst): Incident
    {
        $this->crisisdienst = $crisisdienst;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocatie()
    {
        return $this->locatie;
    }

    /**
     * @param mixed $locatie
     * @return Incident
     */
    public function setLocatie($locatie)
    {
        $this->locatie = $locatie;
        return $this;
    }
}
