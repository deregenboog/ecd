<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_intervisiegroepen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Intervisiegroep
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $startdatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $einddatum;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $medewerker;

    /**
     * @var ArrayCollection|Lidmaatschap[]
     * @ORM\OneToMany(targetEntity="Lidmaatschap", mappedBy="intervisiegroep", cascade={"persist"})
     */
    private $lidmaatschappen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct()
    {
        $this->startdatum = new \DateTime('today');
        $this->lidmaatschappen = new ArrayCollection();
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
        $vrijwilligers = [];

        foreach ($this->lidmaatschappen as $lidmaatschap) {
            try {
                if ($lidmaatschap->getVrijwilliger()
                    && $lidmaatschap->getVrijwilliger()->getVrijwilliger()
                ) {
                    $vrijwilligers[] = $lidmaatschap->getVrijwilliger();
                }
            } catch (EntityNotFoundException $e) {
                // ignore
            }
        }

        $vrijwilligers = array_filter($vrijwilligers);
        usort($vrijwilligers, function (IzVrijwilliger $a, IzVrijwilliger $b) {
            $naamA = $a->getVrijwilliger()->getNaam();
            $naamB = $b->getVrijwilliger()->getNaam();
            return $naamB <=> $naamA;
        });

        return $vrijwilligers;
    }

    public function getLidmaatschappen()
    {
        $lidmaatschappen = [];

        foreach ($this->lidmaatschappen as $lidmaatschap) {
            try {
                if ($lidmaatschap->getVrijwilliger()
                    && $lidmaatschap->getVrijwilliger()->getVrijwilliger()
                ) {
                    $lidmaatschappen[] = $lidmaatschap;
                }
            } catch (EntityNotFoundException $e) {
                // ignore
            }
        }

        $lidmaatschappen = array_filter($lidmaatschappen);
        usort($lidmaatschappen, function (Lidmaatschap $a, Lidmaatschap $b) {
            $naamA = $a->getVrijwilliger()->getVrijwilliger()->getNaam();
            $naamB = $b->getVrijwilliger()->getVrijwilliger()->getNaam();
            return $naamB <=> $naamA;
        });

        return $lidmaatschappen;
    }

    public function addLidmaatschap(Lidmaatschap $lidmaatschap)
    {
        $this->lidmaatschappen[] = $lidmaatschap;
        $lidmaatschap->setIntervisiegroep($this);

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }
}
