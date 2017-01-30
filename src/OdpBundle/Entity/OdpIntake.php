<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_intakes")
 * @ORM\HasLifecycleCallbacks
 */
class OdpIntake
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="intake_datum", type="date")
     */
    private $intakeDatum;

    /**
     * @ORM\Column(name="gezin_met_kinderen", type="boolean")
     */
    private $gezinMetKinderen;

    /**
     * @var OdpDeelnemer
     * @ORM\OneToOne(targetEntity="OdpDeelnemer", inversedBy="odpIntake")
     */
    private $odpDeelnemer;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medewerker;

    public function getId()
    {
        return $this->id;
    }

    public function getIntakeDatum()
    {
        return $this->intakeDatum;
    }

    public function getOdpDeelnemer()
    {
        return $this->odpDeelnemer;
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
}
