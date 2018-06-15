<?php

namespace GaBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class GaActiviteit
{
    use TimestampableTrait;

    const STATUS_AANWEZIG = 'Aanwezig';
    const STATUS_AFWEZIG = 'Afwezig';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var GaGroep
     *
     * @ORM\ManyToOne(targetEntity="GaGroep", inversedBy="gaActiviteiten")
     * @ORM\JoinColumn(name="groepsactiviteiten_groep_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $gaGroep;

    /**
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(name="time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $tijd;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $afgesloten;

    /**
     * @var ArrayCollection|GaKlantDeelname[]
     *
     * @ORM\OneToMany(targetEntity="GaKlantDeelname", mappedBy="gaActiviteit")
     */
    private $gaKlantDeelnames;

    /**
     * @var ArrayCollection|GaVrijwilligerDeelname[]
     *
     * @ORM\OneToMany(targetEntity="GaVrijwilligerDeelname", mappedBy="gaActiviteit")
     */
    private $gaVrijwilligerDeelnames;

    public function __construct()
    {
        $this->gaKlantDeelnames = new ArrayCollection();
        $this->gaVrijwilligerDeelnames = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
}
