<?php

namespace TwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
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
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait, NotDeletableTrait;


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


//
//
//
//    /**
//     * @return mixed
//     */
//    public function getMinVal()
//    {
//        return $this->minVal;
//    }
//
//    /**
//     * @param mixed $minVal
//     */
//    public function setMinVal($minVal): void
//    {
//        $this->minVal = $minVal;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getMaxVal()
//    {
//        return $this->maxVal;
//    }
//
//    /**
//     * @param mixed $maxVal
//     */
//    public function setMaxVal($maxVal): void
//    {
//        $this->maxVal = $maxVal;
//    }


}
