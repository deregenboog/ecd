<?php

namespace TwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_duurthuisloos")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class DuurThuisloos
{
    use ActivatableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="label")
     * @Gedmo\Versioned
     */
    private $label;

    /**
     * @ORM\Column(name="minVal", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $minVal;

    /**
     * @ORM\Column(name="maxVal", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $maxVal;


    public function __toString(): string
    {
        return $this->label;
    }

    private function mndJr($val)
    {
        if($val < 12)
        {
            return $val ." mnd";
        }
        else
        {
            return ($val/12) ." Jr";
        }
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
     * @return DuurThuisloos
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getMinVal()
    {
        return $this->minVal;
    }

    /**
     * @param mixed $minVal
     */
    public function setMinVal($minVal): void
    {
        $this->minVal = $minVal;
    }

    /**
     * @return mixed
     */
    public function getMaxVal()
    {
        return $this->maxVal;
    }

    /**
     * @param mixed $maxVal
     */
    public function setMaxVal($maxVal): void
    {
        $this->maxVal = $maxVal;
    }


}
