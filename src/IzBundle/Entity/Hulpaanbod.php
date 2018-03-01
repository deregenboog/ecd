<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_koppelingen")
 * @Gedmo\Loggable
 */
class Hulpaanbod extends Koppeling
{
    /**
     * @var IzVrijwilliger
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="izHulpaanbiedingen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $izVrijwilliger;

    /**
     * @var Hulpvraag
     * @ORM\OneToOne(targetEntity="Hulpvraag")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
     * @Gedmo\Versioned
     */
    private $hulpvraag;

    public function getIzVrijwilliger()
    {
        return $this->izVrijwilliger;
    }

    public function setIzVrijwilliger(IzVrijwilliger $izVrijwilliger)
    {
        $this->izVrijwilliger = $izVrijwilliger;

        return $this;
    }

    public function getHulpvraag()
    {
        return $this->hulpvraag;
    }

    public function setHulpvraag(Hulpvraag $hulpvraag)
    {
        $this->hulpvraag = $hulpvraag;

        return $this;
    }

    public function isGekoppeld()
    {
        return $this->hulpvraag instanceof Hulpvraag;
    }
}
