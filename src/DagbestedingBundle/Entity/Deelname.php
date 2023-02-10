<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="dagbesteding_deelnames",
 *     indexes={}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Deelname
{
    use IdentifiableTrait;
    use ActivatableTrait;

    /**
     * @var Traject
     *
     * @ORM\ManyToOne(targetEntity="DagbestedingBundle\Entity\Traject", inversedBy="deelnames")
     * @Gedmo\Versioned
     */
    private $traject;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="DagbestedingBundle\Entity\Project", inversedBy="deelnames")
     * @Gedmo\Versioned
     */
    private $project;

    /**
     * @var Beschikbaarheid
     *
     * @ORM\OneToOne(targetEntity="Beschikbaarheid", mappedBy="deelname", cascade={"persist", "remove"})
     */
    private $beschibaarheid;

    /**
     * @var bool
     *
     * @ORM\Column(name="`active`", type="boolean", options={"default":1})
     * @Gedmo\Versioned
     */
    protected $actief = true;

    public function __construct(Traject $traject = null, Project $project = null)
    {
        $this->traject = $traject;
        $this->project = $project;
    }

    public function __toString()
    {
        return $this->getProject()->getNaam();
    }

    /**
     * @return Traject
     */
    public function getTraject()
    {
        return $this->traject;
    }

    /**
     * @param Traject $traject
     */
    public function setTraject($traject)
    {
        $this->traject = $traject;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    public function getBeschikbaarheid(): ?Beschikbaarheid
    {
        return $this->beschibaarheid;
    }

    public function setBeschikbaarheid(Beschikbaarheid $beschikbaarheid): self
    {
        $this->beschibaarheid = $beschikbaarheid;
        $beschikbaarheid->setDeelname($this);

        return $this;
    }
}
