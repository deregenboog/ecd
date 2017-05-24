<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="verslagen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verslag
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @ORM\ManyToOne(targetEntity="Klant")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @ORM\ManyToOne(targetEntity="Medewerker")
     * @Gedmo\Versioned
     */
    private $medewerker;
}
