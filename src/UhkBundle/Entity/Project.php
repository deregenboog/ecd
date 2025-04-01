<?php

namespace UhkBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="uhk_projecten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Project
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
    use TimestampableTrait;
    use NotDeletableTrait;

    /**
     * @var Deelnemer[]
     *
     * @ORM\OneToMany(targetEntity="UhkBundle\Entity\Deelnemer",mappedBy="project")
     */
    private $deelnemers;

    /**
     * @var string
     * @ORM\Column(name="color", type="string", length=10, nullable=true)
     * 
     */
    private $color;

    

    public function getColor():string{
        return $this->color;
    }

    public function setColor(string $color){
        $this->color = $color;
        return $this;
    }


}
