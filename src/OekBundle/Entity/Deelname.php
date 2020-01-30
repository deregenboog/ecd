<?php

namespace OekBundle\Entity;

use AppBundle\Model\KlantRelationInterface;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_deelnames")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Deelname implements KlantRelationInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Training
     *
     * @ORM\ManyToOne(targetEntity="Training", inversedBy="deelnames")
     * @ORM\JoinColumn(name="oekTraining_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $training;

    /**
     * @var Deelnemer
     *
     * @ORM\ManyToOne(targetEntity="Deelnemer", inversedBy="deelnames")
     * @ORM\JoinColumn(name="oekKlant_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $deelnemer;

    /**
     * History of states.
     *
     * @var DeelnameStatus[]
     *
     * @ORM\OneToMany(targetEntity="DeelnameStatus", cascade={"persist"}, mappedBy="deelname")
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $deelnameStatussen;

    /**
     * Current state.
     *
     * @var DeelnameStatus
     *
     * @ORM\OneToOne(targetEntity="DeelnameStatus", cascade={"persist"})
     * @ORM\JoinColumn(name="oekDeelnameStatus_id")
     * @Gedmo\Versioned
     */
    private $deelnameStatus;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $doorverwezenNaar;

    public function __construct(Training $training = null, Deelnemer $deelnemer = null)
    {
        $this->training = $training;
        $this->deelnemer = $deelnemer;
        $this->deelnameStatus = new DeelnameStatus($this);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTraining()
    {
        return $this->training;
    }

    public function setTraining(Training $training)
    {
        $this->training = $training;

        return $this;
    }

    public function getDeelnemer()
    {
        return $this->deelnemer;
    }

    public function setDeelnemer(Deelnemer $deelnemer)
    {
        $this->deelnemer = $deelnemer;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->deelnameStatus->getStatus();
    }

    public function setStatus($status)
    {
        $deelnameStatus = new DeelnameStatus($this);
        $deelnameStatus->setStatus($status);
        $this->setDeelnameStatus($deelnameStatus);
    }

    public function getDeelnameStatus()
    {
        return $this->deelnameStatus;
    }

    public function setDeelnameStatus(DeelnameStatus $deelnameStatus)
    {
        $this->deelnameStatussen[] = $deelnameStatus;

        $this->deelnameStatus = $deelnameStatus;

        return $this;
    }

    public function isDeletable()
    {
        return DeelnameStatus::STATUS_AANGEMELD === $this->deelnameStatus->getStatus();
    }

    public function getKlant()
    {
        return $this->getDeelnemer();
    }

    public function getKlantFieldName()
    {
      return "Deelnemer";
    }

    /**
     * @return string
     */
    public function getDoorverwezenNaar():? string
    {
        return $this->doorverwezenNaar;
    }

    /**
     * @param string $doorverwezenNaar
     */
    public function setDoorverwezenNaar(?string $doorverwezenNaar): void
    {
        $this->doorverwezenNaar = $doorverwezenNaar;
    }


}
