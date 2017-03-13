<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_verslagen")
 * @ORM\HasLifecycleCallbacks
 */
class OdpVerslag
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $opmerking;

    /**
     * @var OdpDeelnemer
     * @ORM\OneToOne(targetEntity="OdpDeelnemer", inversedBy="odpIntake")
     */
    private $odpDeelnemer;

    /**
     * @var OdpHuurovereenkomst
     * @ORM\OneToOne(targetEntity="OdpHuurovereenkomst")
     */
    private $odpHuurovereenkomst;

    public function getId()
    {
        return $this->id;
    }

    public function getOpmerking()
    {
        return $this->opmerking;
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;

        return $this;
    }
}
