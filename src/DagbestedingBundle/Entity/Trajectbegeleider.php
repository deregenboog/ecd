<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Entity\Medewerker;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_trajectbegeleiders")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Trajectbegeleider
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var ArrayCollection|Traject[]
     *
     * @ORM\OneToMany(targetEntity="Traject", mappedBy="begeleider")
     */
    private $trajecten;

    public function __construct()
    {
        $this->trajecten = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->medewerker;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function isDeletable()
    {
        return 0 === count($this->trajecten);
    }

    public function getTrajecten()
    {
        return $this->trajecten;
    }
}
