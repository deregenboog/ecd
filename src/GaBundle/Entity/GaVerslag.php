<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_verslagen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({"Klant" = "GaKlantVerslag", "Vrijwilliger" = "GaVrijwilligerVerslag"})
 */
abstract class GaVerslag
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
     */
    protected $medewerker;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $opmerking;

    public function getId()
    {
        return $this->id;
    }
}
