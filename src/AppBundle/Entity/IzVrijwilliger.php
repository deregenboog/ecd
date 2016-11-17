<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class IzVrijwilliger extends IzDeelnemer
{
    /**
     * @var Vrijwilliger
     * @ORM\OneToOne(targetEntity="Vrijwilliger")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     */
    protected $vrijwilliger;

    /**
     * @var ArrayCollection|IzKoppeling[]
     * @ORM\OneToMany(targetEntity="IzKoppeling", mappedBy="izVrijwilliger")
     */
    private $izHulpaanbiedingen;

    public function __construct()
    {
        $this->izHulpaanbiedingen = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->vrijwilliger;
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    public function setVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }
}
