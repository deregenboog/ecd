<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="intakes")
 * @Gedmo\Loggable
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
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_intake", type="date")
     * @Gedmo\Versioned
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
     * @Gedmo\Versioned
     */
    private $toegangInloophuis;

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;
    }
}
