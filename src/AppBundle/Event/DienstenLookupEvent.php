<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Klant;

class DienstenLookupEvent extends Event
{
    /**
     * @var int
     */
    private $klantId;

    /**
     * @var Klant
     */
    private $klant;

    /**
     * @var array
     */
    private $diensten;

    public function __construct($klantId, array $diensten = [])
    {
        $this->klantId = $klantId;
        $this->diensten = $diensten;
    }

    public function getKlantId()
    {
        return $this->klantId;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getDiensten()
    {
        return $this->diensten;
    }

    public function addDienst(array $dienst)
    {
        $this->diensten[] = $dienst;

        return $this;
    }
}
