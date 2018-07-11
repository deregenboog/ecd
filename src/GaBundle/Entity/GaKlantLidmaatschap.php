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
class GaKlantLidmaatschap extends GaLidmaatschap
{
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="GaGroep", inversedBy="gaKlantLeden")
     * @ORM\JoinColumn(name="groepsactiviteiten_groep_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $gaGroep;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(name="klant_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $klant;

    public function __construct(GaGroep $gaGroep, Klant $klant)
    {
        parent::__construct($gaGroep);
        $this->klant = $klant;
    }

    public function getKlant()
    {
        return $this->klant;
    }
}
