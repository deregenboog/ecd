<?php

namespace AppBundle\Form\Model;

class AppIntRangeModel
{
    /**
     * @var int
     */
    private $low;

    /**
     * @var int
     */
    private $high;

    public function __construct($low = null, $high = null)
    {
        $this->low = $low;
        $this->high = $high;
    }

    public function hasData()
    {
        return is_integer($this->low)
            || is_integer($this->high);
    }

    /**
     * @return int
     */
    public function getLow()
    {
        return $this->low;
    }

    /**
     * @param int $low
     *
     * @return AppIntRangeModel
     */
    public function setLow($low)
    {
        $this->low = $low;

        return $this;
    }

    /**
     * @return int
     */
    public function getHigh()
    {
        return $this->high;
    }

    /**
     * @param int $high
     *
     * @return AppIntRangeModel
     */
    public function setHigh($high)
    {
        $this->high = $high;

        return $this;
    }

    private function validate()
    {
        if ($this->low instanceof \DateTime
            && $this->high instanceof \DateTime
            && $this->high > $this->low
        ) {
            // ?
        }
    }
}
