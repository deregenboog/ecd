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
     * @ORM\Column(name="datum_tot", type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $datumTot;

    /**
     * @var Locatietijd[]
     *
     * @ORM\OneToMany(targetEntity="Locatietijd", mappedBy="locatie")
     */
    private $locatietijden;

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
     * @return multitype:\InloopBundle\Entity\Locatietijd
     */
    public function getLocatietijden()
    {
        return $this->locatietijden;
    }

    /**
     * @param multitype:\InloopBundle\Entity\Locatietijd $locatietijden
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

        $openingTimeCorrection = \Configure::read('openingTimeCorrectionSec');

        foreach ($this->locatietijden as $tijd) {
            if ($tijd->getDagVanDeWeek() == $date->format('w')) {
                $tijd->getOpeningstijd()
                    ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                    ->modify("-{$openingTimeCorrection} seconds")
                ;
                $tijd->getSluitingstijd()
                    ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                    ->modify("+{$openingTimeCorrection} seconds")
                ;
                if ($date > $tijd->getOpeningstijd() && $date < $tijd->getSluitingstijd()) {
                    return true;
                }
            }

            if ($tijd->getDagVanDeWeek() == $prevDate->format('w')) {
                $tijd->getOpeningstijd()
                    ->setDate($prevDate->format('Y'), $prevDate->format('m'), $prevDate->format('d'))
                    ->modify("-{$openingTimeCorrection} seconds")
                ;
                $tijd->getSluitingstijd()
                    ->setDate($prevDate->format('Y'), $prevDate->format('m'), $prevDate->format('d'))
                    ->modify("+{$openingTimeCorrection} seconds")
                ;
                if ($prevDate > $tijd->getOpeningstijd() && $prevDate < $tijd->getSluitingstijd()) {
                    return true;
                }
            }

            if ($tijd->getDagVanDeWeek() == $nextDate->format('w')) {
                $tijd->getOpeningstijd()
                    ->setDate($nextDate->format('Y'), $nextDate->format('m'), $nextDate->format('d'))
                    ->modify("-{$openingTimeCorrection} seconds")
                ;
                $tijd->getSluitingstijd()
                    ->setDate($nextDate->format('Y'), $nextDate->format('m'), $nextDate->format('d'))
                    ->modify("+{$openingTimeCorrection} seconds")
                ;
                if ($nextDate > $tijd->getOpeningstijd() && $nextDate < $tijd->getSluitingstijd()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getLastClosingTime($dayLimit = 0)
    {
        $date =  new \DateTime(sprintf('-%d days', $dayLimit));
        $original = clone $date;

        $count = 7;
        do {
            $closingTime = $this->getClosingTimeByDayOfWeek($date->format('w'));
            $date->modify('-1 day');
            --$count;
            if (0 == $count) {
                break;
            }
        } while (!$closingTime || $closingTime > $original);

        var_dump($closingTime, $original, $count); die;

        return $closingTime;



        var_dump($builder); die;
    }

    public function getClosingTimeByDayOfWeek($dayOfWeek)
    {
        foreach ($this->locatietijden as $locatietijd) {
            if ($dayOfWeek == $locatietijd->getDagVanDeWeek()) {
                return $locatietijd->getSluitingstijd();
            }
        }
    }
}
