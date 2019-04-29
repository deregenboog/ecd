<?php

namespace ScipBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="scip_deelnames",
 *     indexes={}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Deelname
{
    use IdentifiableTrait, ActivatableTrait;

    /**
     * @var Deelnemer
     *
     * @ORM\ManyToOne(targetEntity="Deelnemer", inversedBy="deelnames")
     * @Gedmo\Versioned
     */
    private $deelnemer;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="deelnames")
     * @Gedmo\Versioned
     */
    private $project;

    /**
     * @var Beschikbaarheid
     *
     * @ORM\OneToOne(targetEntity="Beschikbaarheid", mappedBy="deelname", cascade={"persist", "remove"})
     */
    private $beschibaarheid;

    public function __construct(Deelnemer $deelnemer = null, Project $project = null)
    {
        $this->deelnemer = $deelnemer;
        $this->project = $project;
    }

    /**
     * @return Deelnemer
     */
    public function getDeelnemer()
    {
        return $this->deelnemer;
    }

    /**
     * @param Deelnemer $deelnemer
     */
    public function setDeelnemer($deelnemer)
    {
        $this->deelnemer = $deelnemer;

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
