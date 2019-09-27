<?php

namespace GaBundle\Form;

use AppBundle\Form\Model\AppDateRangeModel;
use GaBundle\Entity\Activiteit;

class ActiviteitenReeksModel
{
    /**
     * @var Activiteit
     */
    private $activiteit;

    /**
     * @var \DateTime
     */
    private $tijd;

    /**
     * @var AppDateRangeModel
     */
    private $periode;

    /**
     * @var int
     */
    private $frequentie;

    /**
     * @var string
     */
    private $weekdag;

    public function __construct(Activiteit $activiteit)
    {
        $this->activiteit = $activiteit;
    }

    public function getActiviteit()
    {
        return $this->activiteit;
    }

    public function setActiviteit(Activiteit $activiteit)
    {
        $this->activiteit = $activiteit;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTijd()
    {
        return $this->tijd;
    }

    /**
     * @param \DateTime $tijd
     */
    public function setTijd(\DateTime $tijd)
    {
        $this->tijd = $tijd;

        return $this;
    }

    /**
     * @return AppDateRangeModel
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * @param AppDateRangeModel $periode
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * @return number
     */
    public function getFrequentie()
    {
        return $this->frequentie;
    }

    /**
     * @param number $frequentie
     */
    public function setFrequentie($frequentie)
    {
        if ($frequentie < 0 || $frequentie > 5) {
            throw new \InvalidArgumentException('Frequency must be an integer between 0 and 5');
        }

        $this->frequentie = $frequentie;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeekdag()
    {
        return $this->weekdag;
    }

    /**
     * @param string $weekdag
     */
    public function setWeekdag($weekdag)
    {
        $this->weekdag = $weekdag;

        return $this;
    }
}
