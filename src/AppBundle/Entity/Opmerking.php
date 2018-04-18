<?php

namespace AppBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
