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
class VrijwilligerLidmaatschap extends Lidmaatschap
{
    use TimestampableTrait;

    /**
     * @var Groep
     *
     * @ORM\ManyToOne(targetEntity="Groep", inversedBy="vrijwilligerlidmaatschappen")
     * @ORM\JoinColumn(name="groepsactiviteiten_groep_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $groep;

    /**
     * @var Vrijwilliger
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    public function __construct(Groep $groep = null, Vrijwilliger $vrijwilliger = null)
    {
        parent::__construct($groep);
        $this->vrijwilliger = $vrijwilliger;
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    public function setVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }
}
