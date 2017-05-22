<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="opmerkingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Opmerking
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
