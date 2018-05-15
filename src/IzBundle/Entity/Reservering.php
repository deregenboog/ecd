<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Geslacht;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\IdentifiableTrait;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_reserveringen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable
 */
class Reservering
{
    use IdentifiableTrait, RequiredMedewerkerTrait, TimestampableTrait, SoftDeleteable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    protected $einddatum;

    /**
     * @var Hulpvraag
     * @ORM\ManyToOne(targetEntity="Hulpvraag", inversedBy="reserveringen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $hulpvraag;

    /**
     * @var Hulpaanbod
     * @ORM\ManyToOne(targetEntity="Hulpaanbod", inversedBy="reserveringen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $hulpaanbod;

    public function __construct(Hulpvraag $hulpvraag = null, Hulpaanbod $hulpaanbod = null)
    {
        $this->hulpvraag = $hulpvraag;
        $this->hulpaanbod = $hulpaanbod;
        $this->startdatum = new \DateTime('today');
        $this->einddatum = new \DateTime('+2 weeks');
    }

    public function getHulpvraag()
    {
        return $this->hulpvraag;
    }

    public function setHulpvraag(Hulpvraag $hulpvraag)
    {
        $this->hulpvraag = $hulpvraag;

        return $this;
    }

    public function getHulpaanbod()
    {
        return $this->hulpaanbod;
    }

    public function setHulpaanbod(Hulpaanbod $hulpaanbod)
    {
        $this->hulpaanbod = $hulpaanbod;

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

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(\DateTime $einddatum = null)
    {
        $this->einddatum = $einddatum;

        return $this;
    }
}
