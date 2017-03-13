<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huuraanbiedingen")
 * @ORM\HasLifecycleCallbacks
 */
class OdpHuuraanbod
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var OdpVerhuurder
     * @ORM\ManyToOne(targetEntity="OdpVerhuurder", inversedBy="odpHuuraanbiedingen")
     */
    private $odpVerhuurder;

    /**
     * @var OdpHuurovereenkomst
     * @ORM\OneToOne(targetEntity="OdpHuurovereenkomst", mappedBy="odpHuuraanbod")
     */
    private $odpHuurovereenkomst;

    /**
     * @ORM\Column(type="date")
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $einddatum;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $opmerkingen;

    public function getId()
    {
        return $this->id;
    }

    public function getOdpVerhuurder()
    {
        return $this->odpVerhuurder;
    }

    public function setOdpVerhuurder(OdpVerhuurder $odpVerhuurder)
    {
        $this->odpVerhuurder = $odpVerhuurder;

        return $this;
    }

    public function getKlant()
    {
        return $this->odpVerhuurder->getKlant();
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

    public function getOpmerkingen()
    {
        return $this->opmerkingen;
    }

    public function setOpmerkingen($opmerkingen)
    {
        $this->opmerkingen = $opmerkingen;
    }

    public function getOdpHuurovereenkomst()
    {
        return $this->odpHuurovereenkomst;
    }

    public function isDeletable()
    {
        return false;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
