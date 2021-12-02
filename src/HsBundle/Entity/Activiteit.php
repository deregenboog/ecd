<?php

namespace HsBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_activiteiten")
 * @Gedmo\Loggable
 * @ORM\HasLifeCycleCallbacks
 */
class Activiteit
{
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $naam;


    /**
     * @ORM\ManyToMany(targetEntity="Klus", mappedBy="activiteiten")
     */
    private $klussen;

    public function __construct($naam = null)
    {
        $this->naam = $naam;
        $this->klussen = new ArrayCollection();
        $this->created = $this->modified = new \DateTime();
    }

    public function __toString(): string
    {
        return (string) $this->naam;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    public function getKlussen()
    {
        return $this->klussen;
    }

    public function addKlus(Klus $klus)
    {
        $this->klussen[] = $klus;

        return $this;
    }

    public function isDeletable()
    {
        return 0 === count($this->klussen);
    }
}
