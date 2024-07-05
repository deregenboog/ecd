<?php

namespace AppBundle\Model;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait RequiredMedewerkerTrait
{
    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
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
