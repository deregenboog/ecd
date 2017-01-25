<?php

namespace OdpBundle\Entity;

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
     * @var OdpHuurder
     * @ORM\ManyToOne(targetEntity="OdpHuurder", inversedBy="odpHuurverzoeken")
     */
    private $odpHuurder;

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

    public function isDeletable()
    {
        return false;
    }
}
