<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="groepsactiviteiten_vrijwilligers",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_activiteit_vrijwilliger_idx", columns={"groepsactiviteit_id", "vrijwilliger_id"})}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class VrijwilligerDeelname extends Deelname
{
    /**
     * @var Activiteit
     *
     * @ORM\ManyToOne(targetEntity="Activiteit", inversedBy="vrijwilligerDeelnames")
     * @ORM\JoinColumn(name="groepsactiviteit_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $activiteit;

    /**
     * @var Vrijwilliger
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    public function __construct(Activiteit $activiteit = null, Vrijwilliger $vrijwilliger = null)
    {
        parent::__construct($activiteit);
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
