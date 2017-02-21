<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huurovereenkomsten")
 * @ORM\HasLifecycleCallbacks
 */
class OdpHuurovereenkomst
{
    use TimestampableTrait, RequiredMedewerkerTrait;

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
     * @var OdpHuurovereenkomstAfsluiting
     * @ORM\ManyToOne(targetEntity="OdpHuurovereenkomstAfsluiting")
     */
    protected $odpHuurovereenkomstAfsluiting;

    public function getId()
    {
        return $this->id;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(\DateTime $einddatum = null)
    {
        $this->einddatum = $einddatum;

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
        $this->odpHuuraanbod = $huuraanbod;

        return $this;
    }

    public function setOdpHuurverzoek(OdpHuurverzoek $huurverzoek)
    {
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

    public function isDeletable()
    {
        return false;
    }
}
