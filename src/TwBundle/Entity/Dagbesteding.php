<?php

namespace TwBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_dagbesteding")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Dagbesteding
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="label", type="string", nullable=false)
     * @Gedmo\Versioned
     */
    private $label;


    public function __toString(): string
    {
        return $this->getLabel();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }




}
