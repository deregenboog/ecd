<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huurverzoeken")
 * @ORM\HasLifecycleCallbacks
 */
class Huurverzoek
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Huurder
     * @ORM\ManyToOne(targetEntity="Huurder", inversedBy="huurverzoeken")
     */
    private $huurder;

    /**
     * @var Huurovereenkomst
     * @ORM\OneToOne(targetEntity="Huurovereenkomst", mappedBy="huurverzoek")
     */
    private $huurovereenkomst;

    /**
     * @ORM\Column(type="date")
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $afsluitdatum;

    /**
     * @var HuurverzoekAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="HuurverzoekAfsluiting", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $afsluiting;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="odp_huurverzoek_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    public function __construct()
    {
        $this->verslagen = new ArrayCollection();
    }

    public function __toString()
    {
        if ($this->afsluitdatum) {
            return sprintf(
                '%s (%s t/m %s)',
                $this->huurder,
                $this->startdatum->format('d-m-Y'),
                $this->afsluitdatum->format('d-m-Y')
            );
        }

        return sprintf(
            '%s (vanaf %s)',
            $this->huurder,
            $this->startdatum->format('d-m-Y')
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHuurder()
    {
        return $this->huurder;
    }

    public function setHuurder(Huurder $huurder)
    {
        $this->huurder = $huurder;

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

    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum(\DateTime $afsluitdatum = null)
    {
        $this->afsluitdatum = $afsluitdatum;

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
        return $this->afsluiting === null;
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

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(HuurverzoekAfsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }
}
