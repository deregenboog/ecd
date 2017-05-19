<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

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

    public function getNachtopvang()
    {
        return $this->nachtopvang;
    }

    public function setNachtopvang($nachtopvang)
    {
        $this->nachtopvang = $nachtopvang;

        return $this;
    }

    public function getGebruikersruimte()
    {
        return $this->gebruikersruimte;
    }

    public function setGebruikersruimte($gebruikersruimte)
    {
        $this->gebruikersruimte = $gebruikersruimte;

        return $this;
    }

    public function getMaatschappelijkWerk()
    {
        return $this->maatschappelijkWerk;
    }

    public function setMaatschappelijkWerk($maatschappelijkWerk)
    {
        $this->maatschappelijkWerk = $maatschappelijkWerk;

        return $this;
    }

    public function getTbcCheck()
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
}
