<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_groepen_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class GaVrijwilligerLidmaatschap extends GaLidmaatschap
{
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="GaGroep", inversedBy="gaVrijwilligerLeden")
     * @ORM\JoinColumn(name="groepsactiviteiten_groep_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $gaGroep;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    public function __construct(GaGroep $gaGroep, Vrijwilliger $vrijwilliger)
    {
        parent::__construct($gaGroep);
        $this->vrijwilliger = $vrijwilliger;
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }
}
