<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\IzHulpvraagRepository")
 * @ORM\Table(name="iz_koppelingen")
 * @Gedmo\Loggable
 */
class IzHulpvraag extends IzKoppeling
{
    /**
     * @var IzKlant
     * @ORM\ManyToOne(targetEntity="IzKlant", inversedBy="izHulpvragen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $izKlant;

    /**
     * @var IzHulpaanbod
     * @ORM\OneToOne(targetEntity="IzHulpaanbod")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
     * @Gedmo\Versioned
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

    public function setIzHulpaanbod(IzHulpaanbod $izHulpaanbod = null)
    {
        $this->izHulpaanbod = $izHulpaanbod;

        return $this;
    }

    public function isGekoppeld()
    {
        return $this->izHulpaanbod instanceof IzHulpaanbod;
    }
}
