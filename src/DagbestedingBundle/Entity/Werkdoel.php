<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\MedewerkerSubjectInterface;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="dagbesteding_werkdoelen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Werkdoel implements MedewerkerSubjectInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $tekst;

    /**
     * @var Deelnemer
     *
     * @ORM\ManyToOne(targetEntity="Deelnemer")
     *
     * @Gedmo\Versioned
     */
    protected $deelnemer;

    /**
     * @var Traject
     *
     * @ORM\ManyToOne(targetEntity="DagbestedingBundle\Entity\Traject", inversedBy="werkdoelen")
     *
     * @Gedmo\Versioned
     */
    protected $traject;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    protected $datum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct()
    {
        $this->datum = new \DateTime();
    }

    public function getDeelnemer()
    {
        return $this->deelnemer;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function setDeelnemer(Deelnemer $deelnemer)
    {
        $this->deelnemer = $deelnemer;

        return $this;
    }

    public function getTekst()
    {
        return $this->tekst;
    }

    public function setTekst($tekst)
    {
        $this->tekst = $tekst;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getTraject(): Traject
    {
        return $this->traject;
    }

    public function setTraject(Traject $traject): Werkdoel
    {
        $this->traject = $traject;

        return $this;
    }
}
