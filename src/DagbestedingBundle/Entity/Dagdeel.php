<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="dagbesteding_dagdelen",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_traject_datum_dagdeel_idx", columns={"traject_id", "datum", "dagdeel"})}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Dagdeel
{
    const DAGDEEL_OCHTEND = 'ochtend';
    const DAGDEEL_MIDDAG = 'middag';
    const DAGDEEL_AVOND = 'avond';

    const DAGDELEN = [
        self::DAGDEEL_OCHTEND,
        self::DAGDEEL_MIDDAG,
        self::DAGDEEL_AVOND,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Traject
     * @ORM\ManyToOne(targetEntity="Traject", inversedBy="dagdelen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $traject;

    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $project;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(nullable=false)
     * @Gedmo\Versioned
     */
    private $dagdeel;

    /**
     * @ORM\Column(nullable=false)
     * @Gedmo\Versioned
     */
    private $aanwezigheid;

    /**
     * @param Project $project
     * @param \DateTime $datum
     * @param string $dagdeel
     * @throws \InvalidArgumentException
     */
    public function __construct(Project $project, \DateTime $datum, $dagdeel, $aanwezigheid = null)
    {
        $this->project = $project;
        $this->datum = $datum;
        $this->dagdeel = $dagdeel;
        $this->aanwezigheid = $aanwezigheid;

        if (!in_array($dagdeel, self::DAGDELEN)) {
            throw new \InvalidArgumentException('Dagdeel "'.$dagdeel.'" is niet geldig.');
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTraject()
    {
        return $this->traject;
    }

    public function setTraject(Traject $traject = null)
    {
        $this->traject = $traject;

        return $this;
    }

    public function getDatum()
    {
        return $this->datum;
    }

//     public function setDatum($datum)
//     {
//         $this->datum = $datum;

//         return $this;
//     }

    public function getDagdeel()
    {
        return $this->dagdeel;
    }

    public function getProject()
    {
        return $this->project;
    }

//     public function setDagdeel($dagdeel)
//     {
//         $this->dagdeel = $dagdeel;

//         return $this;
//     }

    /**
     * @param Dagdeel $dagdeel
     *
     * @return bool
     */
    public function isEqualTo(Dagdeel $dagdeel)
    {
        return $this->getProject() == $dagdeel->getProject()
            && $this->getDatum() == $dagdeel->getDatum()
            && $this->getDagdeel() == $dagdeel->getDagdeel()
        ;
    }

    public function getAanwezigheid()
    {
        return $this->aanwezigheid;
    }

    public function setAanwezigheid($aanwezigheid)
    {
        $this->aanwezigheid = $aanwezigheid;

        return $this;
    }
}
