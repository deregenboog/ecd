<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Vrijwilliger;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class GaVrijwilligerIntake extends GaIntake
{
    /**
     * @var Vrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

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
