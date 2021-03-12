<?php

namespace InloopBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="locaties")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Locatie
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="naam", nullable=false)
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(name="nachtopvang", type="boolean", nullable=false, options={"default"=0})
     * @Gedmo\Versioned
     */
    private $nachtopvang = false;

    /**
     * @ORM\Column(name="gebruikersruimte", type="boolean", nullable=false, options={"default"=0})
     * @Gedmo\Versioned
     */
    private $gebruikersruimte = false;

    /**
     * @ORM\Column(name="maatschappelijkwerk", type="boolean", nullable=false, options={"default"=0})
     * @Gedmo\Versioned
     */
    private $maatschappelijkWerk = false;

    /**
     * @ORM\Column(name="tbc_check", type="boolean", nullable=false, options={"default"=0})
     * @Gedmo\Versioned
     */
    private $tbcCheck = false;

    /**
     * @ORM\Column(name="wachtlijst", type="boolean", nullable=true, options={"default"=0})
     * @Gedmo\Versioned
     */
    private $wachtlijst = false;

    /**
     * @ORM\Column(name="datum_van", type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $datumVan;

    /**
     * @ORM\Column(name="datum_tot", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $datumTot;

    /**
     * @var Locatietijd[]
     *
     * @ORM\OneToMany(targetEntity="Locatietijd", mappedBy="locatie", cascade={"persist"})
     * @ORM\OrderBy({"dagVanDeWeek": "ASC"})
     */
    private $locatietijden;

    private $openingTimeCorrection = 30 * 60; // 30 minutes

    public function __toString()
    {
        return $this->naam;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getNachtopvang()
    {
        return $this->nachtopvang;
    }

    public function isNachtopvang()
    {
        return $this->nachtopvang;
    }

    public function setNachtopvang($nachtopvang)
    {
        $this->nachtopvang = $nachtopvang;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getGebruikersruimte()
    {
        return $this->gebruikersruimte;
    }

    public function isGebruikersruimte()
    {
        return $this->gebruikersruimte;
    }

    public function setGebruikersruimte($gebruikersruimte)
    {
        $this->gebruikersruimte = $gebruikersruimte;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getMaatschappelijkWerk()
    {
        return $this->maatschappelijkWerk;
    }

    public function isMaatschappelijkWerk()
    {
        return $this->maatschappelijkWerk;
    }

    public function setMaatschappelijkWerk($maatschappelijkWerk)
    {
        $this->maatschappelijkWerk = $maatschappelijkWerk;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getTbcCheck()
    {
        return $this->tbcCheck;
    }

    public function isTbcCheck()
    {
        return $this->tbcCheck;
    }

    public function setTbcCheck($tbcCheck)
    {
        $this->tbcCheck = $tbcCheck;

        return $this;
    }

    public function getDatumVan()
    {
        return $this->datumVan;
    }

    public function setDatumVan(\DateTime $datumVan)
    {
        $this->datumVan = $datumVan;

        return $this;
    }

    public function getDatumTot()
    {
        return $this->datumTot;
    }

    public function setDatumTot(\DateTime $datumTot)
    {
        $this->datumTot = $datumTot;

        return $this;
    }

    /**
     * @return Locatietijd[]
     */
    public function getLocatietijden()
    {
        return $this->locatietijden;
    }

    /**
     * @return Locatietijd
     */
    public function getLocatietijd($dayOfWeek)
    {
        $dayOfWeek = (int) $dayOfWeek;
        while ($dayOfWeek >= 7) {
            $dayOfWeek = $dayOfWeek % 7;
        }
        foreach ($this->locatietijden as $locatietijd) {
            if ($dayOfWeek === $locatietijd->getDagVanDeWeek()) {
                return $locatietijd;
            }
        }
    }

    /**
     * @param Locatietijd $locatietijd
     */
    public function addLocatietijd(Locatietijd $locatietijd)
    {
        $this->locatietijden[] = $locatietijd;
        $locatietijd->setLocatie($this);

        return $this;
    }

    /**
     * @param Locatietijd[] $locatietijden
     */
    public function setLocatietijden($locatietijden)
    {
        $this->locatietijden = $locatietijden;

        return $this;
    }

    public function isOpen(\DateTime $date = null)
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime();
        }
        $locatietijd = $this->getLocatietijd($date->format('w'));
        if ($locatietijd) {
            $openingstijd = (clone $locatietijd->getOpeningstijd())
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify("-{$this->openingTimeCorrection} seconds")
            ;
            $sluitingstijd = (clone $locatietijd->getSluitingstijd())
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify("+{$this->openingTimeCorrection} seconds")
            ;
            if ($openingstijd > $sluitingstijd) {
                $sluitingstijd->modify('+1 day');
            }
            if ($date >= $openingstijd && $date <= $sluitingstijd) {
                return true;
            }
        }

        $locatietijd = $this->getLocatietijd($date->format('w') - 1);
        if ($locatietijd && $locatietijd->getOpeningstijd() > $locatietijd->getSluitingstijd()) {
            $openingstijd = (clone $locatietijd->getOpeningstijd())
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify('-1 day')
                ->modify("-{$this->openingTimeCorrection} seconds")
            ;
            $sluitingstijd = (clone $locatietijd->getSluitingstijd())
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify('-1 day')
                ->modify("+{$this->openingTimeCorrection} seconds")
            ;
            if ($openingstijd > $sluitingstijd) {
                $sluitingstijd->modify('+1 day');
            }
            if ($date >= $openingstijd && $date <= $sluitingstijd) {
                return true;
            }
        }

        return false;
    }

    public function getClosingTimeByDayOfWeek($dayOfWeek)
    {
        foreach ($this->locatietijden as $locatietijd) {
            if ($dayOfWeek == $locatietijd->getDagVanDeWeek()) {
                return $locatietijd->getSluitingstijd();
            }
        }
    }

    public function isDeletable()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isWachtlijst(): bool
    {
        return $this->wachtlijst;
    }

    /**
     * @param bool $wachtlijst
     * @return Locatie
     */
    public function setWachtlijst(bool $wachtlijst): Locatie
    {
        $this->wachtlijst = $wachtlijst;
        return $this;
    }


}
