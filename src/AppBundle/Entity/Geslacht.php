<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="geslachten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Geslacht
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
    private $afkorting;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $volledig;

    public function __construct($volledig, $afkorting = null)
    {
        $this->volledig = $volledig;
        $this->afkorting = $afkorting;
    }

    public function __toString()
    {
        return $this->volledig;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAfkorting()
    {
        return $this->afkorting;
    }

    public function getVolledig()
    {
        return $this->volledig;
    }
}
