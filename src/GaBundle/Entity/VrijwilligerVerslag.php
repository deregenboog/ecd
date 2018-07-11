<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class VrijwilligerVerslag extends Verslag
{
    /**
     * @var Vrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    public function __construct(Vrijwilliger $vrijwilliger)
    {
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
