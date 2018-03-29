<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpvraagRepository")
 * @ORM\Table(name="iz_koppelingen")
 * @Gedmo\Loggable
 */
class Hulpvraag extends Koppeling
{
    /**
     * @var IzKlant
     * @ORM\ManyToOne(targetEntity="IzKlant", inversedBy="izHulpvragen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $izKlant;

    /**
     * @var Hulpaanbod
     * @ORM\OneToOne(targetEntity="Hulpaanbod")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
     * @Gedmo\Versioned
     */
    protected $hulpaanbod;

    /**
     * @var Hulpvraagsoort
     * @ORM\ManyToOne(targetEntity="Hulpvraagsoort")
     * @Gedmo\Versioned
     */
    protected $hulpvraagsoort;

    /**
     * @var bool
     *
     * @ORM\Column(name="expat", type="boolean", nullable=false)
     */
    private $geschiktVoorExpat = false;

    public function __construct()
    {
        parent::__construct();
        $this->secundarieHulpvraagsoorten = new ArrayCollection();
        $this->doelgroepen = new ArrayCollection();
    }

    public function getIzKlant()
    {
        return $this->izKlant;
    }

    public function setIzKlant(IzKlant $izKlant)
    {
        $this->izKlant = $izKlant;

        return $this;
    }

    public function getHulpaanbod()
    {
        return $this->hulpaanbod;
    }

    public function setHulpaanbod(Hulpaanbod $hulpaanbod = null)
    {
        $this->hulpaanbod = $hulpaanbod;

        return $this;
    }

    public function isGekoppeld()
    {
        return $this->hulpaanbod instanceof Hulpaanbod;
    }

    public function getHulpvraagsoort()
    {
        return $this->hulpvraagsoort;
    }

    public function setHulpvraagsoort(Hulpvraagsoort $hulpvraagsoort)
    {
        $this->hulpvraagsoort = $hulpvraagsoort;

        return $this;
    }

    public function isGeschiktVoorExpat()
    {
        return $this->geschiktVoorExpat;
    }

    public function setGeschiktVoorExpat($geschiktVoorExpat)
    {
        $this->geschiktVoorExpat = (bool) $geschiktVoorExpat;

        return $this;
    }
}
