<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_koppelingen")
 */
class IzHulpvraag extends IzKoppeling
{
    /**
     * @var IzKlant
     * @ORM\ManyToOne(targetEntity="IzKlant", inversedBy="izHulpvragen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     */
    protected $izKlant;

    /**
     * @var IzHulpaanbod
     * @ORM\OneToOne(targetEntity="IzHulpaanbod")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
     */
    protected $izHulpaanbod;

    public function getIzKlant()
    {
        return $this->izKlant;
    }

    public function setIzKlant(IzKlant $izKlant)
    {
        $this->izKlant = $izKlant;

        return $this;
    }

    public function getIzHulpaanbod()
    {
        return $this->izHulpaanbod;
    }

    public function setIzHulpaanbod(IzHulpaanbod $izHulpaanbod)
    {
        $this->izHulpaanbod = $izHulpaanbod;

        return $this;
    }
}
