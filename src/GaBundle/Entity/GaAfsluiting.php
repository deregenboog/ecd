<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_afsluitingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class GaAfsluiting
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $naam;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }
}
