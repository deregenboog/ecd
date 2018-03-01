<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Vrijwilliger;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\IzVrijwilligerRepository")
 * @Gedmo\Loggable
 */
class IzVrijwilliger extends IzDeelnemer
{
    /**
     * @var Vrijwilliger
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(name="foreign_key", nullable=true)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    /**
     * @var MatchingVrijwilliger
     * @ORM\OneToOne(targetEntity="MatchingVrijwilliger", mappedBy="izVrijwilliger")
     * @Gedmo\Versioned
     */
    protected $matching;

    /**
     * @var ArrayCollection|IzHulpaanbod[]
     * @ORM\OneToMany(targetEntity="IzHulpaanbod", mappedBy="izVrijwilliger")
     * @ORM\OrderBy({"startdatum" = "DESC", "koppelingStartdatum" = "DESC"})
     */
    private $izHulpaanbiedingen;

    /**
     * @var ArrayCollection|Intervisiegroep[]
     * @ORM\ManyToMany(targetEntity="Intervisiegroep", inversedBy="vrijwilligers")
     * @ORM\JoinTable(
     *     name="iz_deelnemers_iz_intervisiegroepen",
     *     joinColumns={@ORM\JoinColumn(name="iz_deelnemer_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="iz_intervisiegroep_id")}
     * )
     * @ORM\OrderBy({"naam": "asc"})
     */
    private $intervisiegroepen;

    /**
     * @var BinnengekomenVia
     * @ORM\ManyToOne(targetEntity="BinnengekomenVia")
     * @ORM\JoinColumn(name="binnengekomen_via")
     * @Gedmo\Versioned
     */
    protected $binnengekomenVia;

    public function __construct()
    {
        $this->izHulpaanbiedingen = new ArrayCollection();
        $this->intervisiegroepen = new ArrayCollection();
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

    public function getBinnengekomenVia()
    {
        return $this->binnengekomenVia;
    }

    public function setBinnengekomenVia(BinnengekomenVia $binnengekomenVia)
    {
        $this->binnengekomenVia = $binnengekomenVia;

        return $this;
    }

    public function getMatching()
    {
        return $this->matching;
    }

    public function getIntervisiegroepen()
    {
        return $this->intervisiegroepen;
    }
}
