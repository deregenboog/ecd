<?php

namespace TwBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Verhuurder extends Deelnemer
{
    /**
     * @var VerhuurderAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="VerhuurderAfsluiting", inversedBy="verhuurders", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    protected $afsluiting;

    /**
     * @var ArrayCollection|Huuraanbod[]
     *
     * @ORM\OneToMany(targetEntity="Huuraanbod", mappedBy="verhuurder", cascade={"persist"})
     *
     * @ORM\OrderBy({"startdatum" = "DESC", "id" = "DESC"})
     */
    private $huuraanbiedingen;

    /**
     * @var Pandeigenaar
     *
     * @ORM\ManyToOne(targetEntity="Pandeigenaar", inversedBy="verhuurders")
     *
     * @Gedmo\Versioned
     */
    private $pandeigenaar;

    /**
     * @var string
     *
     * @ORM\Column(name="pandeigenaar_toelichting", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $pandeigenaarToelichting;

    /**
     * "Kleine schuld, grote winst".
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $ksgw = false;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Project", inversedBy="verhuurders", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    private $project;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $verhuurprijs;

    /** @var bool
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $huurtoeslag;

    /**
     * @var AanvullingInkomen
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\AanvullingInkomen", inversedBy="verhuurders")
     * Gedmo\Versioned
     */
    private $aanvullingInkomen;

    /**
     * @var Kwijtschelding
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Kwijtschelding", inversedBy="verhuurders")
     * Gedmo\Versioned
     */
    private $kwijtschelding;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $samenvatting;

    public function __construct(?AppKlant $appKlant = null)
    {
        if (null !== $appKlant) {
            $this->appKlant = $appKlant;
        }

        parent::__construct();

        $this->huuraanbiedingen = new ArrayCollection();
    }

    public function isActief()
    {
        return null === $this->afsluiting;
    }

    public function isClosable()
    {
        $today = new \DateTime('today');

        $actieveHuuraanbiedingen = array_filter($this->huuraanbiedingen->toArray(), function (Huuraanbod $huuraanbod) use ($today) {
            if ($huuraanbod->getAfsluitdatum() && $huuraanbod->getAfsluitdatum() <= $today) {
                return false;
            }
            $huurovereenkomst = $huuraanbod->getHuurovereenkomst();
            if ($huurovereenkomst && $huurovereenkomst->getAfsluitdatum() && $huurovereenkomst->getAfsluitdatum() <= $today) {
                return false;
            }

            return true;
        });

        return 0 === count($actieveHuuraanbiedingen);
    }

    public function isDeletable()
    {
        return 0 === count($this->huuraanbiedingen)
            && 0 === count($this->documenten)
            && 0 === count($this->verslagen);
    }

    public function getHuuraanbiedingen()
    {
        return $this->huuraanbiedingen;
    }

    public function addHuuraanbod(Huuraanbod $huuraanbod)
    {
        $this->huuraanbiedingen[] = $huuraanbod;
        $huuraanbod->setVerhuurder($this);

        return $this;
    }

    public function getHuurovereenkomsten()
    {
        $huurovereenkomsten = [];
        foreach ($this->huuraanbiedingen as $huuraanbod) {
            if ($huuraanbod->getHuurovereenkomst()) {
                $huurovereenkomsten[] = $huuraanbod->getHuurovereenkomst();
            }
        }

        usort($huurovereenkomsten, function ($huurovereenkomst1, $huurovereenkomst2) {
            if ($huurovereenkomst1->getStartdatum() < $huurovereenkomst2->getStartdatum()) {
                return 1;
            } elseif ($huurovereenkomst1->getStartdatum() > $huurovereenkomst2->getStartdatum()) {
                return -1;
            } else {
                return 0;
            }
        });

        return $huurovereenkomsten;
    }

    public function isGekoppeld()
    {
        // los van een eventuele koppeling moet worden gekeken of er een open huuraanbieding is.

        $has = $this->getHuuraanbiedingen();
        foreach ($has as $ha) {
            if (!$ha->getHuurovereenkomst()) {
                $afsluitdatum = $ha->getAfsluitdatum();
                if (null === $afsluitdatum) {
                    return false;
                }
            }
        }

        // er zijn geen open huuraanbiedingen, dan kijken naar de huurovereenkomsten
        $today = new \DateTime('today');
        $hoes = $this->getHuurovereenkomsten();
        foreach ($hoes as $hoe) {
            /** @var Huurovereenkomst $hoe */
            if (false == $hoe->isReservering()
                && true == $hoe->isActief()
                && (null == $hoe->getAfsluitdatum() || $hoe->getAfsluitdatum() > $today)
                && null != $hoe->getStartdatum()
            ) {
                return true;
            }
        }

        return false;
    }

    public function getPandeigenaar()
    {
        return $this->pandeigenaar;
    }

    public function setPandeigenaar(?Pandeigenaar $pandeigenaar): self
    {
        $this->pandeigenaar = $pandeigenaar;

        return $this;
    }

    public function getPandeigenaarToelichting()
    {
        return $this->pandeigenaarToelichting;
    }

    public function setPandeigenaarToelichting($pandeigenaarToelichting)
    {
        $this->pandeigenaarToelichting = $pandeigenaarToelichting;

        return $this;
    }

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(VerhuurderAfsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }

    public function isKsgw()
    {
        return (bool) $this->ksgw;
    }

    public function setKsgw($ksgw)
    {
        $this->ksgw = $ksgw;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): Verhuurder
    {
        $this->project = $project;

        return $this;
    }

    public function getKwijtschelding(): ?Kwijtschelding
    {
        return $this->kwijtschelding;
    }

    public function setKwijtschelding(?Kwijtschelding $kwijtschelding): Verhuurder
    {
        $this->kwijtschelding = $kwijtschelding;

        return $this;
    }

    public function getVerhuurprijs(): ?int
    {
        return $this->verhuurprijs;
    }

    public function setVerhuurprijs(int $verhuurprijs): Verhuurder
    {
        $this->verhuurprijs = $verhuurprijs;

        return $this;
    }

    public function isHuurtoeslag(): ?bool
    {
        return $this->huurtoeslag;
    }

    public function setHuurtoeslag(?bool $huurtoeslag): Verhuurder
    {
        $this->huurtoeslag = $huurtoeslag;

        return $this;
    }

    public function getAanvullingInkomen(): ?AanvullingInkomen
    {
        return $this->aanvullingInkomen;
    }

    public function setAanvullingInkomen(?AanvullingInkomen $aanvullingInkomen): Verhuurder
    {
        $this->aanvullingInkomen = $aanvullingInkomen;

        return $this;
    }

    public function getSamenvatting(): ?string
    {
        return $this->samenvatting;
    }

    public function setSamenvatting(?string $samenvatting): self
    {
        $this->samenvatting = $samenvatting;

        return $this;
    }
}
