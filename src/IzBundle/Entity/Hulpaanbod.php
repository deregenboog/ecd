<?php

namespace IzBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpaanbodRepository")
 * @Gedmo\Loggable
 */
class Hulpaanbod extends Hulp
{
    /**
     * @var IzVrijwilliger
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="hulpaanbiedingen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $izVrijwilliger;

    /**
     * @var Koppeling
     * @ORM\OneToOne(targetEntity="Koppeling", mappedBy="hulpaanbod", cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $koppeling;

    /**
     * @var Hulpvraagsoort[]
     * @ORM\ManyToMany(targetEntity="Hulpvraagsoort")
     */
    protected $hulpvraagsoorten;

    /**
     * @var Reservering
     * @ORM\OneToMany(targetEntity="Reservering", mappedBy="hulpaanbod")
     */
    protected $reserveringen;

    public function __construct()
    {
        parent::__construct();
        $this->hulpvraagsoorten = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s | %s | %s', $this->izVrijwilliger, $this->project, $this->startdatum->format('d-m-Y'));
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

    public function setKoppeling(Koppeling $koppeling = null)
    {
        $this->koppeling = $koppeling;
        $koppeling->setHulpaanbod($this);

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

    public function getVerslagen($includeRelated = true)
    {
        if (!$includeRelated || !$this->isGekoppeld()) {
            return $this->verslagen;
        }

        $verslagen = array_merge(
            $this->verslagen->toArray(),
            $this->hulpvraag->getVerslagen(false)->toArray()
        );
        usort($verslagen, function (Verslag $a, Verslag $b) {
            $datumA = $a->getCreated();
            $datumB = $b->getCreated();

            return $datumA < $datumB ? 1 : -1;
        });

        return $verslagen;
    }

    public function setKoppelingStartdatum(\DateTime $koppelingStartdatum = null, $setRelated = true)
    {
        $this->koppelingStartdatum = $koppelingStartdatum;

        if ($koppelingStartdatum && !$this->tussenevaluatiedatum) {
            $tussenevaluatiedatum = clone $koppelingStartdatum;
            $tussenevaluatiedatum->modify('+3 months');
            $this->setTussenevaluatiedatum($tussenevaluatiedatum);
        }

        if ($koppelingStartdatum && !$this->eindevaluatiedatum) {
            $eindevaluatiedatum = clone $koppelingStartdatum;
            $eindevaluatiedatum->modify('+6 months');
            $this->setEindevaluatiedatum($eindevaluatiedatum);
        }

        if ($setRelated) {
            $this->hulpvraag->setKoppelingStartdatum($koppelingStartdatum, false);
            $this->hulpvraag->setTussenevaluatiedatum($this->tussenevaluatiedatum);
            $this->hulpvraag->setEindevaluatiedatum($this->eindevaluatiedatum);
        }

        return $this;
    }

    public function setTussenevaluatiedatum(\DateTime $datum = null, $setRelated = true)
    {
        $this->tussenevaluatiedatum = $datum;
        if ($setRelated) {
            $this->hulpvraag->setTussenevaluatiedatum($datum, false);
        }

        return $this;
    }

    public function setEindevaluatiedatum(\DateTime $datum = null, $setRelated = true)
    {
        $this->eindevaluatiedatum = $datum;
        if ($setRelated) {
            $this->hulpvraag->setEindevaluatiedatum($datum, false);
        }

        return $this;
    }

    public function setKoppelingEinddatum(\DateTime $koppelingEinddatum = null, $setRelated = true)
    {
        $this->koppelingEinddatum = $koppelingEinddatum;
        if ($setRelated) {
            $this->hulpvraag->setKoppelingEinddatum($koppelingEinddatum, false);
        }

        return $this;
    }

    /**
     * @param AfsluitredenKoppeling $afsluitredenKoppeling
     */
    public function setAfsluitredenKoppeling(AfsluitredenKoppeling $afsluitredenKoppeling, $setRelated = true)
    {
        $this->afsluitredenKoppeling = $afsluitredenKoppeling;
        if ($setRelated) {
            $this->hulpvraag->setAfsluitredenKoppeling($afsluitredenKoppeling, false);
        }

        return $this;
    }

    /**
     * @param bool $koppelingSuccesvol
     */
    public function setKoppelingSuccesvol($koppelingSuccesvol, $setRelated = true)
    {
        $this->koppelingSuccesvol = (bool) $koppelingSuccesvol;
        if ($setRelated) {
            $this->hulpvraag->setKoppelingSuccesvol($koppelingSuccesvol, false);
        }

        return $this;
    }
}
