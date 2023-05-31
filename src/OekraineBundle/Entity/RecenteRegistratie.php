<?php

namespace OekraineBundle\Entity;

use AppBundle\Entity\Klant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oekraine_registraties_recent")
 */
class RecenteRegistratie
{
    /**
     * @var Registratie
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Registratie")
     */
    private $registratie;

    /**
     * @var Locatie
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Locatie")
     */
    private $locatie;

    /**
     * @var Bezoeker
     * @ORM\ManyToOne(targetEntity="Bezoeker")
     */
    private $bezoeker;

    /**
     * @var \DateTime
     * @ORM\Column(name="max_buiten", type="datetime")
     */
    private $buiten;

    public function __construct(Registratie $registratie = null)
    {
        if ($registratie && $registratie->getBuiten()) {
            $this->registratie = $registratie;
            $this->locatie = $registratie->getLocatie();
            $this->bezoeker = $registratie->getBezoeker();
            $this->buiten = $registratie->getBuiten();
        }
    }
}
