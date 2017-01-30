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
abstract class OdpHuurovereenkomst
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="datetime")
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
        $this->medewerker = $medewerker;

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
}
