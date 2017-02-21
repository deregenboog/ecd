<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;

trait RequiredMedewerkerTrait
{
    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
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
