<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_intakes")
 * @ORM\HasLifecycleCallbacks
 */
class IzIntake
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @todo Fix typo modifed => modified
     *
     * @ORM\Column(name="modifed", type="datetime")
     */
    protected $modified;

    /**
     * @ORM\Column(name="intake_datum", type="date")
     */
    private $intakeDatum;

    /**
     * @ORM\Column(name="gezin_met_kinderen", type="boolean", nullable=true)
     */
    private $gezinMetKinderen;

    /**
     * @var IzDeelnemer
     * @ORM\OneToOne(targetEntity="IzDeelnemer", inversedBy="izIntake")
     * @ORM\JoinColumn(name="iz_deelnemer_id")
     */
    private $izDeelnemer;

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

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = $this->modified = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->modified = new \DateTime();
    }

    public function getIntakeDatum()
    {
        return $this->intakeDatum;
    }

    public function getIzDeelnemer()
    {
        return $this->izDeelnemer;
    }

    public function isGezinMetKinderen()
    {
        return $this->gezinMetKinderen;
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

    public function setIzDeelnemer(IzDeelnemer $izDeelnemer)
    {
        $this->izDeelnemer = $izDeelnemer;

        return $this;
    }
}
