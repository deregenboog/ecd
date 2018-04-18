<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\SoftDeleteableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_koppelingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable
 */
class Koppeling
{
    use IdentifiableTrait, SoftDeleteableTrait, TimestampableTrait;

    /**
     * @var Hulpvraag
     * @ORM\OneToOne(targetEntity="Hulpvraag", inversedBy="koppeling")
     * @Gedmo\Versioned
     */
    private $hulpvraag;

    /**
     * @var Hulpaanbod
     * @ORM\OneToOne(targetEntity="Hulpaanbod", inversedBy="koppeling")
     * @Gedmo\Versioned
     */
    private $hulpaanbod;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\ManyToOne(targetEntity="Koppelingstatus")
     * @Gedmo\Versioned
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var AfsluitredenKoppeling
     * @ORM\ManyToOne(targetEntity="AfsluitredenKoppeling")
     * @ORM\JoinColumn(name="iz_eindekoppeling_id")
     * @Gedmo\Versioned
     */
    private $afsluitreden;

    /**
     * @ORM\Column(name="succesvol", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $succesvol;

    /**
     * @var Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ORM\OrderBy({"datum": "desc"})
     */
    private $verslagen;

    public function __toString()
    {
        return sprintf('%s - %s', $this->hulpvraag->getIzKlant(), $this->hulpaanbod->getIzVrijwilliger());
    }

    /**
     * @return IzKlant
     */
    public function getIzKlant()
    {
        return $this->hulpvraag->getIzKlant();
    }

    /**
     * @return Hulpvraag
     */
    public function getHulpvraag()
    {
        return $this->hulpvraag;
    }

    /**
     * @param Hulpvraag $hulpvraag
     */
    public function setHulpvraag($hulpvraag)
    {
        $this->hulpvraag = $hulpvraag;

        return $this;
    }

    /**
     * @return Hulpaanbod
     */
    public function getHulpaanbod()
    {
        return $this->hulpaanbod;
    }

    /**
     * @param Hulpaanbod $hulpaanbod
     */
    public function setHulpaanbod($hulpaanbod)
    {
        $this->hulpaanbod = $hulpaanbod;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    public function __construct(Hulpvraag $hulpvraag = null, Hulpaanbod $hulpaanbod = null)
    {
        $this->hulpvraag = $hulpvraag;
        $this->hulpaanbod = $hulpaanbod;

        $this->startdatum = new \DateTime('today');
        $this->verslagen = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum(\DateTime $afsluitdatum = null)
    {
        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }

    public function getAfsluitreden()
    {
        return $this->afsluitreden;
    }

    public function setAfsluitreden(AfsluitredenKoppeling $afsluitreden)
    {
        $this->afsluitreden = $afsluitreden;

        return $this;
    }

    public function isAfgesloten()
    {
        return $this->afsluitdatum instanceof \DateTime
            && $this->afsluitdatum <= new \DateTime('today');
    }

    /**
     * @return bool
     */
    public function isSuccesvol()
    {
        return $this->succesvol;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;
    }

    public function getEvaluaties()
    {
        $evaluaties = [];

        foreach ($this->verslagen as $verslag) {
            if ($verslag instanceof Evaluatie) {
                $evaluaties[] = $verslag;
            }
        }

        return $evaluaties;
    }

    public function addEvaluatie(Evaluatie $evaluatie)
    {
        return $this->addVerslag($evaluatie);
    }
}
