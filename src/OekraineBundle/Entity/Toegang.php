<?php

namespace OekraineBundle\Entity;

use AppBundle\Entity\Klant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oekraine_toegang")
 * @ORM\HasLifecycleCallbacks
 */
class Toegang
{
    /**
     * @var Bezoeker
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Bezoeker")
     */
    private $bezoeker;

    /**
     * @var Locatie
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Locatie")
     */
    private $locatie;

    public function __construct(Bezoeker $bezoeker, Locatie $locatie)
    {
        $this->bezoeker = $bezoeker;
        $this->locatie = $locatie;
    }

    public function getBezoeker()
    {
        return $this->bezoeker;
    }

    public function getLocatie()
    {
        return $this->locatie;
    }
}
