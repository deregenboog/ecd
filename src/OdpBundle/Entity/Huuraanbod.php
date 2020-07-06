<?php

namespace OdpBundle\Entity;

use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huuraanbiedingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
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
     * @Gedmo\Versioned
     */
    private $verhuurder;

    /**
     * @var VormVanOvereenkomst
     * @ORM\ManyToOne(targetEntity="VormVanOvereenkomst", inversedBy="huuraanbod")
     * @Gedmo\Versioned
     */
    private $vormvanovereenkomst;

    /**
     * @var Huurovereenkomst
     * @ORM\OneToOne(targetEntity="Huurovereenkomst", mappedBy="huuraanbod")
     * @Gedmo\Versioned
     */
    private $huurovereenkomst;


    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var HuuraanbodAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="HuuraanbodAfsluiting", inversedBy="huuraanbiedingen", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluiting;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $datumToestemmingAangevraagd;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $datumToestemmingToegekend;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="odp_huuraanbod_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    public function __construct()
    {

        $this->startdatum = new \DateTime();

        $this->verslagen = new ArrayCollection();
    }

    public function __toString()
    {
        if ($this->afsluitdatum) {
            return sprintf(
                '%s (%s t/m %s)',
                $this->verhuurder,
                $this->startdatum->format('d-m-Y'),
                $this->afsluitdatum->format('d-m-Y')
            );
        }

        return sprintf(
            '%s (vanaf %s)',
            $this->verhuurder,
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
        return null === $this->afsluiting;
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

    public function setAfsluiting(HuuraanbodAfsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }

    public function getDatumToestemmingAangevraagd()
    {
        return $this->datumToestemmingAangevraagd;
    }

    public function setDatumToestemmingAangevraagd($datumToestemmingAangevraagd = null)
    {
        $this->datumToestemmingAangevraagd = $datumToestemmingAangevraagd;

        return $this;
    }

    public function getDatumToestemmingToegekend()
    {
        return $this->datumToestemmingToegekend;
    }

    public function setDatumToestemmingToegekend($datumToestemmingToegekend = null)
    {
        $this->datumToestemmingToegekend = $datumToestemmingToegekend;

        return $this;
    }

    /**
     * @return Huurverzoek
     */
    public function getReservering(): Huurverzoek
    {
        return $this->reservering;
    }

    /**
     * @param Huurverzoek $reservering
     */
    public function setReservering(Huurverzoek $reservering): void
    {
        $this->reservering = $reservering;
    }

    /**
     * @return VormVanOvereenkomst
     */
    public function getVormvanovereenkomst(): VormVanOvereenkomst
    {
        return $this->vormvanovereenkomst;
    }

    /**
     * @param VormVanOvereenkomst $vormvanovereenkomst
     */
    public function setVormvanovereenkomst(VormVanOvereenkomst $vormvanovereenkomst): void
    {
        $this->vormvanovereenkomst = $vormvanovereenkomst;
    }





}
