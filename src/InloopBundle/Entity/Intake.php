<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="intakes")
 */
class Intake
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="intakes")
     */
    private $klant;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $medewerker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_intake", type="date")
     */
    private $intakedatum;

    private $verblijfsstatus;

    private $postadres;

    private $postcode;

    private $woonplaats;

    /**
     * @var bool
     *
     * @ORM\Column(name="toegang_inloophuis", type="boolean")
     */
    private $toegangInloophuis;

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;
    }
}
