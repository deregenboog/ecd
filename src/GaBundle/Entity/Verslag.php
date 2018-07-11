<?php

namespace GaBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_verslagen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({"Klant" = "KlantVerslag", "Vrijwilliger" = "VrijwilligerVerslag"})
 * @Gedmo\Loggable
 */
abstract class Verslag
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $opmerking;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    /**
     * @param mixed $medewerker
     */
    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpmerking()
    {
        return $this->opmerking;
    }

    /**
     * @param mixed $opmerking
     */
    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;

        return $this;
    }
}
