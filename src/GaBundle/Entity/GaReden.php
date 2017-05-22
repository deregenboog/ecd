<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_redenen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class GaReden
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(length=100, nullable=true)
     * @Gedmo\Versioned
     */
    private $naam;

    public function getId()
    {
        return $this->id;
    }
}
