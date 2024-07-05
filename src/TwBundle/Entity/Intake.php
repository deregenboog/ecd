<?php

namespace TwBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="tw_intakes")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Intake
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @ORM\Column(name="intake_datum", type="date")
     *
     * @Gedmo\Versioned
     */
    private $intakeDatum;

    /**
     * @ORM\Column(name="gezin_met_kinderen", type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $gezinMetKinderen;

    /**
     * @var Deelnemer
     *
     * @ORM\OneToOne(targetEntity="Deelnemer")
     *
     * @Gedmo\Versioned
     */
    private $deelnemer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function getIntakeDatum()
    {
        return $this->intakeDatum;
    }

    public function getDeelnemer()
    {
        return $this->deelnemer;
    }
}
