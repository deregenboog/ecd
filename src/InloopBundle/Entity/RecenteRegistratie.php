<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Klant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="registraties_recent")
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
     * @ORM\ManyToOne(targetEntity="Locatie")
     */
    private $locatie;

    /**
     * @var Klant
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     */
    private $klant;

    /**
     * @var \DateTime
     * @ORM\Column(name="max_buiten", type="datetime")
     */
    private $buiten;
}
