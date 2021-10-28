<?php

namespace TwBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class HuurovereenkomstAfsluiting extends Afsluiting
{
    /**
     * @var Huurovereenkomst
     *
     * @ORM\OneToMany(targetEntity="Huurovereenkomst", mappedBy="afsluiting")
     */
    protected $huurovereenkomsten;

    public function __construct()
    {
        $this->huurovereenkomsten = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->huurovereenkomsten);
    }
}
