<?php

namespace OdpBundle\Entity;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huurverzoeken")
 * @ORM\HasLifecycleCallbacks
 */
class OdpHuurverzoek
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $medewerker;

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
     * @var string
     * @ORM\Column(type="text")
     */
    private $opmerkingen;

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
        $this->medewerker = $medewerker;

        return $this;
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
