<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpvraagRepository")
 * @ORM\Table(name="iz_koppelingen")
 * @Gedmo\Loggable
 */
class Hulpvraag extends Koppeling
{
    /**
     * @var IzKlant
     * @ORM\ManyToOne(targetEntity="IzKlant", inversedBy="izHulpvragen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $izKlant;

    /**
     * @var Hulpaanbod
     * @ORM\OneToOne(targetEntity="Hulpaanbod")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
     * @Gedmo\Versioned
     */
    protected $hulpaanbod;

    public function getIzKlant()
    {
        return $this->izKlant;
    }

    public function setIzKlant(IzKlant $izKlant)
    {
        $this->izKlant = $izKlant;

        return $this;
    }

    public function getHulpaanbod()
    {
        return $this->hulpaanbod;
    }

    public function setHulpaanbod(Hulpaanbod $hulpaanbod = null)
    {
        $this->hulpaanbod = $hulpaanbod;

        return $this;
    }

    public function isGekoppeld()
    {
        return $this->hulpaanbod instanceof Hulpaanbod;
    }
}
