<?php

namespace OdpBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Zrm;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_intakes")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
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
     * @Gedmo\Versioned
     */
    private $intakeDatum;

    /**
     * @ORM\Column(name="gezin_met_kinderen", type="boolean")
     * @Gedmo\Versioned
     */
    private $gezinMetKinderen;

    /**
     * @var Deelnemer
     *
     * @ORM\OneToOne(targetEntity="Deelnemer", inversedBy="intake")
     * @Gedmo\Versioned
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
