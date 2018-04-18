<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpvraagRepository")
 * @Gedmo\Loggable
 */
class Hulpvraag extends Hulp
{
    /**
     * @var IzKlant
     * @ORM\ManyToOne(targetEntity="IzKlant", inversedBy="hulpvragen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $izKlant;

    /**
     * @var Koppeling
     * @ORM\OneToOne(targetEntity="Koppeling", mappedBy="hulpvraag", cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $koppeling;

    /**
     * @var Hulpvraagsoort
     * @ORM\ManyToOne(targetEntity="Hulpvraagsoort")
     * @Gedmo\Versioned
     */
    protected $hulpvraagsoort;

    /**
     * @var Reservering
     * @ORM\OneToMany(targetEntity="Reservering", mappedBy="hulpvraag")
     */
    protected $reserveringen;

    public function __toString()
    {
        return sprintf('%s | %s | %s', $this->izKlant, $this->project, $this->startdatum->format('d-m-Y'));
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

    public function setKoppeling(Koppeling $koppeling = null)
    {
        $this->koppeling = $koppeling;
        $koppeling->setHulpvraag($this);

        return $this;
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

    public function getVerslagen($includeRelated = true)
    {
        if (!$includeRelated || !$this->isGekoppeld()) {
            return $this->verslagen;
        }

        $verslagen = array_merge(
            $this->verslagen->toArray(),
            $this->hulpaanbod->getVerslagen(false)->toArray()
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
            $this->hulpaanbod->setKoppelingStartdatum($koppelingStartdatum, false);
            $this->hulpaanbod->setTussenevaluatiedatum($this->tussenevaluatiedatum);
            $this->hulpaanbod->setEindevaluatiedatum($this->eindevaluatiedatum);
        }

        return $this;
    }

    public function setTussenevaluatiedatum(\DateTime $datum = null, $setRelated = true)
    {
        $this->tussenevaluatiedatum = $datum;
        if ($setRelated) {
            $this->hulpaanbod->setTussenevaluatiedatum($datum, false);
        }

        return $this;
    }

    public function setEindevaluatiedatum(\DateTime $datum = null, $setRelated = true)
    {
        $this->eindevaluatiedatum = $datum;
        if ($setRelated) {
            $this->hulpaanbod->setEindevaluatiedatum($datum, false);
        }

        return $this;
    }

    public function setKoppelingEinddatum(\DateTime $koppelingEinddatum = null, $setRelated = true)
    {
        $this->koppelingEinddatum = $koppelingEinddatum;
        if ($setRelated) {
            $this->hulpaanbod->setKoppelingEinddatum($koppelingEinddatum, false);
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
            $this->hulpaanbod->setAfsluitredenKoppeling($afsluitredenKoppeling, false);
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
            $this->hulpaanbod->setKoppelingSuccesvol($koppelingSuccesvol, false);
        }

        return $this;
    }
}
