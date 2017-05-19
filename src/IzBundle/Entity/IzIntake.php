<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_intakes")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
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
     * @Gedmo\Versioned
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @todo Fix typo modifed => modified
     *
     * @ORM\Column(name="modifed", type="datetime")
     * @Gedmo\Versioned
     */
    private $modified;

    /**
     * @ORM\Column(name="intake_datum", type="date")
     * @Gedmo\Versioned
     */
    private $intakeDatum;

    /**
     * @ORM\Column(name="gezin_met_kinderen", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $gezinMetKinderen;

    /**
     * @var IzDeelnemer
     * @ORM\OneToOne(targetEntity="IzDeelnemer", inversedBy="izIntake")
     * @ORM\JoinColumn(name="iz_deelnemer_id")
     * @Gedmo\Versioned
     */
    private $izDeelnemer;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
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
