<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_groepen_klanten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class KlantLidmaatschap extends Lidmaatschap
{
    use TimestampableTrait;

    /**
     * @var Groep
     *
     * @ORM\ManyToOne(targetEntity="Groep", inversedBy="klantlidmaatschappen")
     * @ORM\JoinColumn(name="groepsactiviteiten_groep_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $groep;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $klant;

    public function __construct(Groep $groep = null, Klant $klant = null)
    {
        parent::__construct($groep);
        $this->klant = $klant;
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
}
