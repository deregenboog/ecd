<?php

namespace InloopBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Klant;

/**
 * @ORM\Entity
 * @ORM\Table(name="inloop_toegang")
 * @ORM\HasLifecycleCallbacks
 */
class Toegang
{
    /**
     * @var Klant
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     */
    private $klant;

    /**
     * @var Locatie
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Locatie")
     */
    private $locatie;

    public function __construct(Klant $klant, Locatie $locatie)
    {
        $this->klant = $klant;
        $this->locatie = $locatie;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function getLocatie()
    {
        return $this->locatie;
    }
}
