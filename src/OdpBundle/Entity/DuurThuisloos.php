<?php

namespace OdpBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_duurthuisloos")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class DuurThuisloos
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

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
        $retStr = "";
        if(isset($this->minVal))
        {
            $retStr .= " > ".$this->mndJr($this->minVal);
        }
        if(isset($this->maxVal))
        {
            $retStr .= " < ".$this->mndJr($this->maxVal);
        }
        $retStr .= "";

        return $retStr;
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
