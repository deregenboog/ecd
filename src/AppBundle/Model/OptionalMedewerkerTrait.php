<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;

trait OptionalMedewerkerTrait
{
    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $medewerker;

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }
}
