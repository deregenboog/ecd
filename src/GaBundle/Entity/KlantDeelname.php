<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Klant;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="groepsactiviteiten_klanten",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_activiteit_klant_idx", columns={"groepsactiviteit_id", "klant_id"})}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class KlantDeelname extends Deelname
{
    /**
     * @var Activiteit
     *
     * @ORM\ManyToOne(targetEntity="Activiteit", inversedBy="klantDeelnames")
     * @ORM\JoinColumn(name="groepsactiviteit_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $activiteit;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $klant;

    public function __construct(Activiteit $activiteit = null, Klant $klant = null)
    {
        parent::__construct($activiteit);
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
