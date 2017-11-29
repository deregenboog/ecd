<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_deelnames")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class OekDeelname
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var OekTraining
     *
     * @ORM\ManyToOne(targetEntity="OekTraining", inversedBy="oekDeelnames")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $oekTraining;

    /**
     * @var OekKlant
     *
     * @ORM\ManyToOne(targetEntity="OekKlant", inversedBy="oekDeelnames")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $oekKlant;

    /**
     * History of states.
     *
     * @var OekDeelnameStatus[]
     *
     * @ORM\OneToMany(targetEntity="OekDeelnameStatus", cascade={"persist"}, mappedBy="oekDeelname")
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $oekDeelnameStatussen;

    /**
     * Current state.
     *
     * @var OekDeelnameStatus
     *
     * @ORM\OneToOne(targetEntity="OekDeelnameStatus", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $oekDeelnameStatus;

    public function __construct(OekTraining $oekTraining = null, OekKlant $oekKlant = null)
    {
        $this->oekTraining = $oekTraining;
        $this->oekKlant = $oekKlant;
        $this->oekDeelnameStatus = new OekDeelnameStatus($this);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOekTraining()
    {
        return $this->oekTraining;
    }

    public function setOekTraining(OekTraining $oekTraining)
    {
        $this->oekTraining = $oekTraining;

        return $this;
    }

    public function getOekKlant()
    {
        return $this->oekKlant;
    }

    public function setOekKlant(OekKlant $oekKlant)
    {
        $this->oekKlant = $oekKlant;

        return $this;
    }

    public function getStatus()
    {
        return $this->oekDeelnameStatus->getStatus();
    }

    public function setStatus($status)
    {
        $oekDeelnameStatus = new OekDeelnameStatus($this);
        $oekDeelnameStatus->setStatus($status);
        $this->setOekDeelnameStatus($oekDeelnameStatus);
    }

    public function getOekDeelnameStatus()
    {
        return $this->oekDeelnameStatus;
    }

    public function setOekDeelnameStatus(OekDeelnameStatus $oekDeelnameStatus)
    {
        $this->oekDeelnameStatussen[] = $oekDeelnameStatus;

        $this->oekDeelnameStatus = $oekDeelnameStatus;

        return $this;
    }

    public function isDeletable()
    {
        return OekDeelnameStatus::STATUS_AANGEMELD === $this->oekDeelnameStatus->getStatus();
    }
}
