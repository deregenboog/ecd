<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Klant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="inloop_toegang")
 *
 * @ORM\HasLifecycleCallbacks
 *
 *
 * Let op: dit is enkel de weerslag van de 'berekening' waar iemand toegang toe heeft.
 * Dit wordt (nog) niet gebruikt om te bewerken oid. Wordt gevuld vanuit de AccessUpdater.
 */
class Toegang
{
    /**
     * @var Klant
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     */
    private $klant;

    /**
     * @var Locatie
     *
     * @ORM\Id
     *
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
