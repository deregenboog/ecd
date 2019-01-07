<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Medewerker;

class Koppeling
{
    /**
     * @var Hulpvraag
     */
    private $hulpvraag;

    /**
     * @var Hulpaanbod
     */
    private $hulpaanbod;

    /**
     * @var \DateTime
     */
    private $startdatum;

    /**
     * @var \DateTime
     */
    private $einddatum;

    /**
     * @var \DateTime
     */
    private $tussenevaluatiedatum;

    /**
     * @var \DateTime
     */
    private $eindevaluatiedatum;

    /**
     * @var bool
     */
    private $succesvol;

    /**
     * @var Succesindicator[]
     */
    private $succesindicatoren;

    public static function create(Hulpvraag $hulpvraag, Hulpaanbod $hulpaanbod)
    {
        $koppeling = new self($hulpvraag, $hulpaanbod);
        $hulpvraag->setKoppeling($koppeling);
        $hulpaanbod->setKoppeling($koppeling);

        if (!$koppeling->getStartdatum()) {
            $koppeling->setStartdatum(new \DateTime('today'));
        }
        if (!$koppeling->getTussenevaluatiedatum()) {
            $koppeling->setTussenevaluatiedatum(new \DateTime('+3 months'));
        }
        if (!$koppeling->getEindevaluatiedatum()) {
            $koppeling->setEindevaluatiedatum(new \DateTime('+6 months'));
        }

        return $koppeling;
    }

    public function __construct(Hulpvraag $hulpvraag, Hulpaanbod $hulpaanbod)
    {
        $this->hulpvraag = $hulpvraag;
        $this->hulpaanbod = $hulpaanbod;
    }

    /**
     * @return Hulpvraag
     */
    public function getHulpvraag()
    {
        return $this->hulpvraag;
    }

    /**
     * @return Hulpaanbod
     */
    public function getHulpaanbod()
    {
        return $this->hulpaanbod;
    }

    /**
     * @return Medewerker
     */
    public function getMedewerker()
    {
        return $this->hulpvraag->getMedewerker();
    }

    /**
     * @param Medewerker $medewerker
     */
    public function setMedewerker(Medewerker $medewerker)
    {
        if ($this->hulpvraag->getMedewerker() !== $medewerker) {
            $this->hulpvraag->setMedewerker($medewerker);
        }
        if ($this->hulpaanbod->getMedewerker() !== $medewerker) {
            $this->hulpaanbod->setMedewerker($medewerker);
        }

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartdatum()
    {
        return $this->hulpvraag->getKoppelingStartdatum();
    }

    /**
     * @param \DateTime $startdatum
     */
    public function setStartdatum(\DateTime $startdatum)
    {
        if ($this->hulpvraag->getKoppelingStartdatum() !== $startdatum) {
            $this->hulpvraag->setKoppelingStartdatum($startdatum);
        }
        if ($this->hulpaanbod->getKoppelingStartdatum() !== $startdatum) {
            $this->hulpaanbod->setKoppelingStartdatum($startdatum);
        }

        $this->setTussenevaluatiedatum((clone $startdatum)->modify('+3 months'));
        $this->setEindevaluatiedatum((clone $startdatum)->modify('+6 months'));

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEinddatum()
    {
        return $this->hulpvraag->getKoppelingEinddatum();
    }

    /**
     * @param \DateTime $einddatum
     */
    public function setEinddatum($einddatum)
    {
        if ($this->hulpvraag->getKoppelingEinddatum() !== $einddatum) {
            $this->hulpvraag->setKoppelingEinddatum($einddatum);
        }
        if ($this->hulpaanbod->getKoppelingEinddatum() !== $einddatum) {
            $this->hulpaanbod->setKoppelingEinddatum($einddatum);
        }

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTussenevaluatiedatum()
    {
        return $this->hulpvraag->getTussenevaluatiedatum();
    }

    /**
     * @param \DateTime $datum
     */
    public function setTussenevaluatiedatum(\DateTime $datum)
    {
        if ($this->hulpvraag->getTussenevaluatiedatum() !== $datum) {
            $this->hulpvraag->setTussenevaluatiedatum($datum);
        }
        if ($this->hulpaanbod->getTussenevaluatiedatum() !== $datum) {
            $this->hulpaanbod->setTussenevaluatiedatum($datum);
        }

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEindevaluatiedatum()
    {
        return $this->hulpvraag->getEindevaluatiedatum();
    }

    /**
     * @param \DateTime $datum
     */
    public function setEindevaluatiedatum(\DateTime $datum)
    {
        if ($this->hulpvraag->getEindevaluatiedatum() !== $datum) {
            $this->hulpvraag->setEindevaluatiedatum($datum);
        }
        if ($this->hulpaanbod->getEindevaluatiedatum() !== $datum) {
            $this->hulpaanbod->setEindevaluatiedatum($datum);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccesvol()
    {
        return $this->succesvol;
    }

    /**
     * @param bool $succesvol
     */
    public function setSuccesvol($succesvol)
    {
        if ($this->hulpvraag->isKoppelingSuccesvol() != $succesvol) {
            $this->hulpvraag->setKoppelingSuccesvol($succesvol);
        }
        if ($this->hulpaanbod->isKoppelingSuccesvol() != $succesvol) {
            $this->hulpaanbod->setKoppelingSuccesvol($succesvol);
        }

        return $this;
    }

    public function setAfsluitreden(AfsluitredenKoppeling $afsluitreden)
    {
        if ($this->hulpvraag->getAfsluitredenKoppeling() !== $afsluitreden) {
            $this->hulpvraag->setAfsluitredenKoppeling($afsluitreden);
        }
        if ($this->hulpaanbod->getAfsluitredenKoppeling() !== $afsluitreden) {
            $this->hulpaanbod->setAfsluitredenKoppeling($afsluitreden);
        }

        return $this;
    }

    public function getSuccesindicatoren()
    {
        return $this->hulpvraag->getSuccesindicatoren();
    }

    public function setSuccesindicatoren($indicatoren)
    {
        return $this->hulpvraag->setSuccesindicatoren($indicatoren);
    }
}
