<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_intakes")
 * @ORM\HasLifecycleCallbacks
 */
class Intake
{
    use TimestampableTrait, RequiredMedewerkerTrait;

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
     * @var Deelnemer
     *
     * @ORM\OneToOne(targetEntity="Deelnemer", inversedBy="intake")
     */
    private $deelnemer;

    public function getId()
    {
        return $this->id;
    }

    public function getIntakeDatum()
    {
        return $this->intakeDatum;
    }

    public function getDeelnemer()
    {
        return $this->deelnemer;
    }
}
