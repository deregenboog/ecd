<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_intervisiegroepen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Intervisiegroep
{
    use IdentifiableTrait, NameableTrait, TimestampableTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $startdatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $einddatum;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $medewerker;

    /**
     * @var ArrayCollection|IzVrijwilliger[]
     *
     * @ORM\ManyToMany(targetEntity="IzVrijwilliger", mappedBy="intervisiegroepen")
     */
    private $vrijwilligers;

    public function __construct()
    {
        $this->startdatum = new \DateTime('today');
        $this->vrijwilligers = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getStartdatum()
    {
        return $this->startdatum;
    }

    /**
     * @param \DateTime $startdatum
     */
    public function setStartdatum($startdatum)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEinddatum()
    {
        return $this->einddatum;
    }

    /**
     * @param \DateTime $einddatum
     */
    public function setEinddatum($einddatum)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Medewerker
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    /**
     * @param \AppBundle\Entity\Medewerker $medewerker
     */
    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getVrijwilligers()
    {
        return $this->vrijwilligers;
    }

    public function isDeletable()
    {
        return false;
    }
}
