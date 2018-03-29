<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpaanbodRepository")
 * @ORM\Table(name="iz_koppelingen")
 * @Gedmo\Loggable
 */
class Hulpaanbod extends Koppeling
{
    /**
     * @var IzVrijwilliger
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="izHulpaanbiedingen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $izVrijwilliger;

    /**
     * @var Hulpvraag
     * @ORM\OneToOne(targetEntity="Hulpvraag")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
     * @Gedmo\Versioned
     */
    private $hulpvraag;

    /**
     * @var Hulpvraagsoort[]
     * @ORM\ManyToMany(targetEntity="Hulpvraagsoort")
     */
    protected $hulpvraagsoorten;

    /**
     * @var bool
     *
     * @ORM\Column(name="expat", type="boolean", nullable=false)
     */
    private $expat = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $coachend = false;

    public function __construct()
    {
        parent::__construct();
        $this->hulpvraagsoorten = new ArrayCollection();
        $this->doelgroepen = new ArrayCollection();
    }

    public function getIzVrijwilliger()
    {
        return $this->izVrijwilliger;
    }

    public function setIzVrijwilliger(IzVrijwilliger $izVrijwilliger)
    {
        $this->izVrijwilliger = $izVrijwilliger;

        return $this;
    }

    public function getHulpvraag()
    {
        return $this->hulpvraag;
    }

    public function setHulpvraag(Hulpvraag $hulpvraag)
    {
        $this->hulpvraag = $hulpvraag;

        return $this;
    }

    public function isGekoppeld()
    {
        return $this->hulpvraag instanceof Hulpvraag;
    }

    public function getDoelgroepen()
    {
        return $this->doelgroepen;
    }

    public function setDoelgroepen($doelgroepen)
    {
        $this->doelgroepen = $doelgroepen;

        return $this;
    }

    public function getHulpvraagsoorten()
    {
        return $this->hulpvraagsoorten;
    }

    public function setHulpvraagsoorten($hulpvraagsoorten)
    {
        $this->hulpvraagsoorten = $hulpvraagsoorten;

        return $this;
    }

    public function isExpat()
    {
        return $this->expat;
    }

    public function setExpat($expat)
    {
        $this->expat = (bool) $expat;

        return $this;
    }

    public function isCoachend()
    {
        return (bool) $this->coachend;
    }

    public function setCoachend($coachend)
    {
        $this->coachend = (bool) $coachend;

        return $this;
    }
}
