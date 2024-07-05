<?php

namespace AppBundle\Form\Model;

class AppDateRangeModel
{
    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    public function __construct(?\DateTime $start = null, ?\DateTime $end = null)
    {
        $this->setStart($start);
        $this->setEnd($end);
    }

    public function hasData()
    {
        return $this->start instanceof \DateTime
            || $this->end instanceof \DateTime;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setStart(?\DateTime $start = null)
    {
        $this->start = $start;
        $this->validate();

        return $this;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd(?\DateTime $end = null)
    {
        $this->end = $end;
        $this->validate();

        return $this;
    }

    private function validate()
    {
        if ($this->start instanceof \DateTime
            && $this->end instanceof \DateTime
            && $this->start > $this->end
        ) {
            $tmp = $this->start;
            $this->start = $this->end;
            $this->end = $tmp;
        }
    }
}
