<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;
use Gedmo\Mapping\Annotation as Gedmo;

trait OptionalMedewerkerTrait
{
    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
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
