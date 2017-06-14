<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_resultaatgebieden")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Resultaatgebied
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Traject
     * @ORM\ManyToOne(targetEntity="Traject", inversedBy="resultaatgebieden")
     * @Gedmo\Versioned
     */
    private $traject;

    /**
     * @var Resultaatgebiedsoort
     * @ORM\ManyToOne(targetEntity="Resultaatgebiedsoort", inversedBy="resultaatgebieden")
     * @Gedmo\Versioned
     */
    private $soort;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    public function __construct(Resultaatgebiedsoort $soort = null)
    {
        $this->soort = $soort;
        $this->setStartdatum(new \DateTime());
    }

    public function __toString()
    {
        return 'Resultaatgebied '.$this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTraject()
    {
        return $this->traject;
    }

    public function setTraject(Traject $traject)
    {
        $this->traject = $traject;

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

    public function isDeletable()
    {
        return false;
    }

    public function getSoort()
    {
        return $this->soort;
    }

    public function setSoort(Resultaatgebiedsoort $soort)
    {
        $this->soort = $soort;

        return $this;
    }
}
