<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class VrijwilligerIntake extends Intake
{
    /**
     * @var Vrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->vrijwilliger);
        } catch (EntityNotFoundException $e) {
            return '';
        }
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
