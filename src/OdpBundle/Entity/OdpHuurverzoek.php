<?php

namespace OdpBundle\Entity;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huurverzoeken")
 * @ORM\HasLifecycleCallbacks
 */
class OdpHuurverzoek
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var OdpHuurder
     * @ORM\ManyToOne(targetEntity="OdpHuurder", inversedBy="odpHuurverzoeken")
     */
    private $odpHuurder;

    /**
     * @var OdpHuurovereenkomst
     * @ORM\OneToOne(targetEntity="OdpHuurovereenkomst", mappedBy="odpHuurverzoek")
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

    public function getOdpHuurder()
    {
        return $this->odpHuurder;
    }

    public function setOdpHuurder(OdpHuurder $odpHuurder)
    {
        $this->odpHuurder = $odpHuurder;

        return $this;
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

    public function getKlant()
    {
        return $this->getOdpHuurder()->getKlant();
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
