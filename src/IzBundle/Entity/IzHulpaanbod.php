<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class IzHulpaanbod extends IzKoppeling
{
    /**
     * @var IzVrijwilliger
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="izHulpaanbiedingen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     */
    private $izVrijwilliger;

    /**
     * @var IzHulpvraag
     * @ORM\OneToOne(targetEntity="IzHulpvraag")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
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
}
