<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_koppelingen")
 * @Gedmo\Loggable
 */
class IzHulpaanbod extends IzKoppeling
{
    /**
     * @var IzVrijwilliger
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="izHulpaanbiedingen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $izVrijwilliger;

    /**
     * @var IzHulpvraag
     * @ORM\OneToOne(targetEntity="IzHulpvraag")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
     * @Gedmo\Versioned
     */
    private $izHulpvraag;

    public function getIzVrijwilliger()
    {
        return $this->izVrijwilliger;
    }

    public function setIzVrijwilliger(IzVrijwilliger $izVrijwilliger)
    {
        $this->izVrijwilliger = $izVrijwilliger;

        return $this;
    }

    public function getIzHulpvraag()
    {
        return $this->izHulpvraag;
    }

    public function setIzHulpvraag(IzHulpvraag $izHulpvraag)
    {
        $this->izHulpvraag = $izHulpvraag;

        return $this;
    }

    public function isGekoppeld()
    {
        return $this->izHulpvraag instanceof IzHulpvraag;
    }
}
