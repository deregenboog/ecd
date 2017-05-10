<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Vrijwilliger;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\IzVrijwilligerRepository")
 */
class IzVrijwilliger extends IzDeelnemer
{
    /**
     * @var Vrijwilliger
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     */
    protected $vrijwilliger;

    /**
     * @var ArrayCollection|IzHulpaanbod[]
     * @ORM\OneToMany(targetEntity="IzHulpaanbod", mappedBy="izVrijwilliger")
     * @ORM\OrderBy({"startdatum" = "DESC", "koppelingStartdatum" = "DESC"})
     */
    private $izHulpaanbiedingen;

    /**
     * @var IzViaPersoon
     * @ORM\ManyToOne(targetEntity="IzViaPersoon")
     * @ORM\JoinColumn(name="binnengekomen_via")
     */
    protected $izBinnengekomenVia;

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

    public function getIzHulpaanbiedingen()
    {
        return $this->izHulpaanbiedingen;
    }

    public function getIzBinnengekomenVia()
    {
        return $this->izBinnengekomenVia;
    }

    public function setIzBinnengekomenVia(IzViaPersoon $izBinnengekomenVia)
    {
        $this->izBinnengekomenVia = $izBinnengekomenVia;

        return $this;
    }
}
