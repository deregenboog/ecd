<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\HasLifeCycleCallbacks
 *
 * @ORM\Table(name="iz_koppelingen",
 *     indexes={
 *
 *       @ORM\Index(name="idx_deelnemer_discr_deleted_einddatum_koppeling", columns={"iz_deelnemer_id","discr","deleted","einddatum","iz_koppeling_id"})
 *     }
 * )
 *
 * @Gedmo\Loggable()
 */
class Koppeling
{
    use TimestampableTrait;
    use IdentifiableTrait;

    /**
     * @var Hulpvraag
     */
    private $hulpvraag;

    /**
     * @var Hulpaanbod
     */
    private $hulpaanbod;

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

    public function __toString()
    {
        return sprintf('%s - %s', $this->hulpvraag->getDeelnemer(), $this->hulpaanbod->getDeelnemer());
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

    public function setEinddatum(?\DateTime $einddatum)
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
        return $this->hulpvraag->isKoppelingSuccesvol();
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

    public function getAfsluitreden()
    {
        return $this->hulpvraag->getAfsluitredenKoppeling();
    }

    public function setAfsluitreden(?AfsluitredenKoppeling $afsluitreden)
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

    public function reopen(): Koppeling
    {
        $this->setEinddatum(null);
        $this->setAfsluitreden(null);

        return $this;
    }
}
