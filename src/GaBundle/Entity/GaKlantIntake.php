<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Klant;
use AppBundle\Model\ZrmTrait;
use AppBundle\Model\ZrmInterface;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class GaKlantIntake extends GaIntake implements ZrmInterface
{
    use ZrmTrait;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     * @Gedmo\Versioned
     */
    protected $klant;

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
