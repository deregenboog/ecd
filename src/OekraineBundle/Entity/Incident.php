<?php

namespace OekraineBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="oekraine_incidenten")
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
     * @ORM\Column(type="boolean")
     */
    private $politie = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $ambulance = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $crisisdienst = false;

    /**
     * @ORM\ManyToOne(targetEntity="Locatie")
     */
    private $locatie;

    /**
     * @ORM\ManyToOne(targetEntity="Bezoeker", inversedBy="incidenten", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull
     */
    private $bezoeker;

    public function __construct(?Bezoeker $bezoeker = null)
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
     * @return Incident
     */
    public function setDatum($datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getOpmerking(): string
    {
        if(is_null($this->opmerking)) return "";
        return mb_convert_encoding($this->opmerking, 'ISO-8859-1','UTF-8');
    }

    /**
     * @return Incident
     */
    public function setOpmerking(string $opmerking = "")
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');

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
