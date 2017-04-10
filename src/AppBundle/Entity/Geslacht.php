<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="geslachten")
 * @ORM\HasLifecycleCallbacks
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
     */
    private $afkorting;

    /**
     * @ORM\Column(type="string")
     */
    private $volledig;

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
