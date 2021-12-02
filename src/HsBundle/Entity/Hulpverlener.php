<?php

namespace HsBundle\Entity;

use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\HasLifeCycleCallbacks
 */
class Hulpverlener
{
    use TimestampableTrait;

    private $naam;

    private $organisatie;

    private $telefoon;

    private $email;

    public function __construct($naam, $organisatie, $telefoon, $email)
    {
        $this->naam = $naam;
        $this->organisatie = $organisatie;
        $this->telefoon = $telefoon;
        $this->email = $email;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    public function getOrganisatie()
    {
        return $this->organisatie;
    }

    public function setOrganisatie($organisatie)
    {
        $this->organisatie = $organisatie;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefoon()
    {
        return $this->telefoon;
    }

    public function setTelefoon($telefoon)
    {
        $this->telefoon = $telefoon;

        return $this;
    }
}
