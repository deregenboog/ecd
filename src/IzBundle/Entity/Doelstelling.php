<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\DoelstellingRepository")
 * @ORM\Table(
 *     name="iz_doelstellingen",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_project_jaar_idx", columns={"project_id", "jaar"})}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Doelstelling
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="IzProject")
     * @Gedmo\Versioned
     */
    private $project;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $jaar;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Werkgebied")
     * @ORM\JoinColumn(name="stadsdeel", referencedColumnName="naam")
     * @Gedmo\Versioned
     */
    private $stadsdeel;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $aantal = 0;

    public function __toString()
    {
        return sprintf('%s (%d)', $this->project, $this->jaar);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getJaar()
    {
        return $this->jaar;
    }

    public function getStadsdeel()
    {
        return $this->stadsdeel;
    }

    public function getAantal()
    {
        return $this->aantal;
    }

    public function setProject(IzProject $project)
    {
        $this->project = $project;

        return $this;
    }

    public function setJaar($jaar)
    {
        $this->jaar = $jaar;

        return $this;
    }

    public function setStadsdeel($stadsdeel)
    {
        $this->stadsdeel = $stadsdeel;

        return $this;
    }

    public function setAantal($aantal)
    {
        $this->aantal = $aantal;

        return $this;
    }
}
