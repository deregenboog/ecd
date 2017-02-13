<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huurovereenkomsten")
 * @ORM\HasLifecycleCallbacks
 */
class OdpHuurovereenkomst
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="date")
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $einddatum;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $medewerker;

    /**
     * @var OdpHuuraanbod
     * @ORM\ManyToOne(targetEntity="OdpHuuraanbod")
     */
    protected $odpHuuraanbod;

    /**
     * @var OdpHuurverzoek
     * @ORM\ManyToOne(targetEntity="OdpHuurverzoek")
     */
    protected $odpHuurverzoek;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $afsluitdatum;

    /**
     * @var OdpHuurovereenkomstAfsluiting
     * @ORM\ManyToOne(targetEntity="OdpHuurovereenkomstAfsluiting")
     */
    protected $odpHuurovereenkomstAfsluiting;

    public function getId()
    {
        return $this->id;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->throwIfNotEditable();

        $this->medewerker = $medewerker;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum = null)
    {
        $this->throwIfNotEditable();

        $this->startdatum = $startdatum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(\DateTime $einddatum = null)
    {
        $this->throwIfNotEditable();

        $this->einddatum = $einddatum;

        return $this;
    }

    public function getAfsluitdatum()
    {
        return $this->einddatum;
    }

    public function setAfsluitdatum(\DateTime $afsluitdatum = null)
    {
        $this->throwIfNotEditable();

        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }

    public function getOdpHuurovereenkomstAfsluiting()
    {
        return $this->odpHuurovereenkomstAfsluiting;
    }

    public function setOdpHuurovereenkomstAfsluiting(OdpHuurovereenkomstAfsluiting $afsluiting)
    {
        $this->throwIfNotEditable();

        $this->odpHuurovereenkomstAfsluiting = $afsluiting;

        return $this;
    }

    public function getOdpHuuraanbod()
    {
        return $this->odpHuuraanbod;
    }

    public function getOdpHuurverzoek()
    {
        return $this->odpHuurverzoek;
    }

    public function setOdpHuuraanbod(OdpHuuraanbod $huuraanbod)
    {
        $this->throwIfNotEditable();

        $this->odpHuuraanbod = $huuraanbod;

        return $this;
    }

    public function setOdpHuurverzoek(OdpHuurverzoek $huurverzoek)
    {
        $this->throwIfNotEditable();

        $this->odpHuurverzoek = $huurverzoek;

        return $this;
    }

    public function getOdpHuurder()
    {
        return $this->odpHuurverzoek->getOdpHuurder();
    }

    public function getOdpVerhuurder()
    {
        return $this->odpHuuraanbod->getOdpVerhuurder();
    }

    public function isEditable()
    {
        return !$this->odpHuurovereenkomstAfsluiting;
    }

    public function isDeletable()
    {
        return $this->isEditable();
    }

    public function __toString()
    {
        return "$this->id";
    }

    private function throwIfNotEditable()
    {
        if (!$this->isEditable()) {
            throw new \Exception("Huurovereenkomst #$this->id is afgesloten en mag daarom niet meer bewerkt worden.");
        }
    }
}
