<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="registraties_recent")
 */
class RecenteRegistratie
{
    /**
     * @var Locatie
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Locatie")
     */
    private $locatie;

    /**
     * @var Klant
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     */
    private $klant;

    /**
     * @var \DateTime
     * @ORM\Column(name="max_buiten", type="datetime")
     */
    private $buiten;
}
