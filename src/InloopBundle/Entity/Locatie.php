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
     * @ORM\OneToMany(targetEntity="Locatietijd", mappedBy="locatie")
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

        $prevDate = (clone $date)->modify('-1 day');
        $nextDate = (clone $date)->modify('+1 day');

        $between = function (\DateTime $date, \DateTime $openingstijd, \DateTime $sluitingstijd) {
            $openingstijd = (clone $openingstijd)
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify("-{$this->openingTimeCorrection} seconds")
            ;
            $sluitingstijd = (clone $sluitingstijd)
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify("+{$this->openingTimeCorrection} seconds")
            ;
            if ($openingstijd > $sluitingstijd) {
                $openingstijd->modify('-1 day');
            }

            if ($date > $openingstijd && $date < $sluitingstijd) {
                return true;
            }

            return false;
        };

        foreach ($this->locatietijden as $locatietijd) {
            if ($locatietijd->getOpeningstijd() <= $locatietijd->getSluitingstijd()
                && $locatietijd->getDagVanDeWeek() == $date->format('w')
            ) {
                if ($between($date, $locatietijd->getOpeningstijd(), $locatietijd->getSluitingstijd())) {
                    return true;
                }
            } elseif ($locatietijd->getOpeningstijd() > $locatietijd->getSluitingstijd()
                && $locatietijd->getDagVanDeWeek() == (clone $date)->modify('-1 day')->format('w')
            ) {
                if ($between($date, $locatietijd->getOpeningstijd(), $locatietijd->getSluitingstijd())) {
                    return true;
                }
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
}
