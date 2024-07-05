<?php

namespace AppBundle\Event;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Model\Dienst;
use Symfony\Contracts\EventDispatcher\Event;

class DienstenVrijwilligerLookupEvent extends Event
{
    /**
     * @var int
     */
    private $vrijwilligerId;

    /**
     * @var Vrijwilliger
     */
    private $vrijwilliger;

    /**
     * @var array
     */
    private $diensten;

    public function __construct($vrijwilligerId, array $diensten = [])
    {
        $this->vrijwilligerId = $vrijwilligerId;
        $this->diensten = $diensten;
    }

    public function getVrijwilligerId()
    {
        return $this->vrijwilligerId;
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    public function setVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }

    public function getDiensten()
    {
        return $this->diensten;
    }

    public function addDienst(Dienst $dienst)
    {
        $this->diensten[] = $dienst;

        return $this;
    }
}
