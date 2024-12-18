<?php

namespace TwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="tw_huurbudget")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Huurbudget
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="minVal", type="integer", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $minVal;

    /**
     * @ORM\Column(name="maxVal", type="integer", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $maxVal;

    public function __toString(): string
    {
        $retStr = '';
        if (isset($this->minVal)) {
            $retStr .= '> '.$this->minVal;
        }
        if (isset($this->maxVal)) {
            $retStr .= '< '.$this->maxVal;
        }
        $retStr .= ' Euro';

        return $retStr;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMinVal()
    {
        return $this->minVal;
    }

    public function setMinVal($minVal): void
    {
        $this->minVal = $minVal;
    }

    public function getMaxVal()
    {
        return $this->maxVal;
    }

    public function setMaxVal($maxVal): void
    {
        $this->maxVal = $maxVal;
    }
}
