<?php

namespace ScipBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="scip_toegangsrechten",
 *     indexes={}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Toegangsrecht
{
    use IdentifiableTrait;

    /**
     * @var Medewerker
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var ArrayCollection|Project[]
     *
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="toegangsrechten")
     */
    private $projecten;

    public function __construct()
    {
        $this->projecten = new ArrayCollection();
    }

    /**
     * @return Medewerker
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    /**
     * @param Medewerker $medewerker
     */
    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjecten()
    {
        return $this->projecten;
    }

    /**
     * @param Collection|Project[]
     */
    public function setProjecten($projecten)
    {
        $this->projecten = $projecten;

        return $this;
    }
}
