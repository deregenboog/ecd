<?php

namespace AppBundle\Model;

use AppBundle\Entity\Klant;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait RequiredKlantTrait
{
    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(name="klant_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $klant;

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
