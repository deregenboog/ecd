<?php

namespace TwBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_coordinatoren")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Coordinator
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __toString()
    {
        return (string) $this->medewerker;
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
}
