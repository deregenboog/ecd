<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huuraanbiedingen")
 * @ORM\HasLifecycleCallbacks
 */
class Huuraanbod
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Verhuurder
     * @ORM\ManyToOne(targetEntity="Verhuurder", inversedBy="huuraanbiedingen")
     */
    private $verhuurder;

    /**
     * @var Huurovereenkomst
     * @ORM\OneToOne(targetEntity="Huurovereenkomst", mappedBy="huuraanbod")
     */
    private $huurovereenkomst;

    /**
     * @ORM\Column(type="date")
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $einddatum;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $actief = true;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="odp_huuraanbod_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    protected $verslagen;

    public function __construct()
    {
        $this->verslagen = new ArrayCollection();
    }

    public function __toString()
    {
        if ($this->einddatum) {
            return sprintf(
                '%s (%s t/m %s)',
                $this->verhuurder,
                $this->startdatum->format('d-m-Y'),
                $this->einddatum->format('d-m-Y')
            );
        }

        return sprintf(
            '%s (vanaf %s)',
            $this->verhuurder->getKlant()->getWerkgebied(),
            $this->startdatum->format('d-m-Y')
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getVerhuurder()
    {
        return $this->verhuurder;
    }

    public function setVerhuurder(Verhuurder $verhuurder)
    {
        $this->verhuurder = $verhuurder;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(\DateTime $einddatum = null)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getHuurovereenkomst()
    {
        return $this->huurovereenkomst;
    }

    public function isDeletable()
    {
        return false;
    }

    public function isActief()
    {
        return $this->actief;
    }

    public function setActief($actief)
    {
        $this->actief = $actief;

        return $this;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;

        return $this;
    }
}
