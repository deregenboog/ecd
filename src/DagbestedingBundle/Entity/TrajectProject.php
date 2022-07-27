<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="dagbesteding_traject_project",
 *     indexes={}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class TrajectProject
{
    use IdentifiableTrait, ActivatableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="DagbestedingBundle\Entity\Traject", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="traject_id", referencedColumnName="id")
     */
    protected $trajecten;

    /**
     * @ORM\ManyToOne(targetEntity="DagbestedingBundle\Entity\Project", cascade={"persist","remove"}, fetch="LAZY" )
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id",nullable=true)
     */
    protected $projecten;

//    /**
//     * @var Beschikbaarheid
//     *
//     * @ORM\OneToOne(targetEntity="Beschikbaarheid", mappedBy="deelname", cascade={"persist", "remove"})
//     */
//    private $beschibaarheid;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getTrajecten()
    {
        return $this->trajecten;
    }

    /**
     * @param mixed $trajecten
     * @return TrajectProject
     */
    public function setTrajecten($trajecten)
    {
        $this->trajecten = $trajecten;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProjecten()
    {
        return $this->projecten;
    }

    /**
     * @param mixed $projecten
     * @return TrajectProject
     */
    public function setProjecten($projecten)
    {
        $this->projecten = $projecten;
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
