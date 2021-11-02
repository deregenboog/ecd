<?php

namespace TwBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verhuurder extends Deelnemer
{
    /**
     * @var VerhuurderAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="VerhuurderAfsluiting", inversedBy="verhuurders", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $afsluiting;

    /**
     * @var ArrayCollection|Huuraanbod[]
     *
     * @ORM\OneToMany(targetEntity="Huuraanbod", mappedBy="verhuurder", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC", "id" = "DESC"})
     */
    private $huuraanbiedingen;

    /**
     *
     */

    /**
     * @var Pandeigenaar
     *
     * @ORM\ManyToOne(targetEntity="Pandeigenaar", inversedBy="verhuurders")
     * @Gedmo\Versioned
     */
    private $pandeigenaar;

    /**
     * @var string
     *
     * @ORM\Column(name="pandeigenaar_toelichting", nullable=true)
     * @Gedmo\Versioned
     */
    private $pandeigenaarToelichting;



    /**
     * "Kleine schuld, grote winst".
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $ksgw = false;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Project", inversedBy="verhuurders", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $project;

//    /**
//     * @var boolean
//     * @ORM\Column(type="boolean",nullable=true)
//     */
//    private $kwijtschelding;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $verhuurprijs;

    /** @var boolean
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $huurtoeslag;


    /**
     * @var AanvullingInkomen
     *
     * ORM\ManyToOne(targetEntity="TwBundle\Entity\AanvullingInkomen", inversedBy="verhuurders")
     * Gedmo\Versioned
     */
//    private $aanvullingInkomen;


    /**
     * @var Kwijtschelding
     *
     * ORM\ManyToOne(targetEntity="TwBundle\Entity\Kwijtschelding", inversedBy="verhuurders")
     * Gedmo\Versioned
     */
//    private $kwijtschelding;



    public function __construct(AppKlant $appKlant = null)
    {
        if(null !== $appKlant){
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

    public function getPandeigenaar()
    {
        return $this->pandeigenaar;
    }

    public function setPandeigenaar(Pandeigenaar $pandeigenaar)
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

    /**
     * @return Project
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     * @return Verhuurder
     */
    public function setProject(Project $project): Verhuurder
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return Kwijtschelding
     */
    public function getKwijtschelding(): ?Kwijtschelding
    {
        return $this->kwijtschelding;
    }

    /**
     * @param Kwijtschelding $kwijtschelding
     * @return Verhuurder
     */
    public function setKwijtschelding(?Kwijtschelding $kwijtschelding): Verhuurder
    {
        $this->kwijtschelding = $kwijtschelding;
        return $this;
    }

//    /**
//     * @return bool
//     */
//    public function isKwijtschelding(): bool
//    {
//        return $this->kwijtschelding;
//    }
//
//    /**
//     * @param bool $kwijtschelding
//     * @return Verhuurder
//     */
//    public function setKwijtschelding(bool $kwijtschelding): Verhuurder
//    {
//        $this->kwijtschelding = $kwijtschelding;
//        return $this;
//    }




    /**
     * @return int
     */
    public function getVerhuurprijs(): ?int
    {
        return $this->verhuurprijs;
    }

    /**
     * @param int $verhuurprijs
     * @return Verhuurder
     */
    public function setVerhuurprijs(int $verhuurprijs): Verhuurder
    {
        $this->verhuurprijs = $verhuurprijs;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isHuurtoeslag(): ?bool
    {
        return $this->huurtoeslag;
    }

    /**
     * @param bool|null $huurtoeslag
     * @return Verhuurder
     */
    public function setHuurtoeslag(?bool $huurtoeslag): Verhuurder
    {
        $this->huurtoeslag = $huurtoeslag;
        return $this;
    }

    /**
     * @return AanvullingInkomen
     */
    public function getAanvullingInkomen(): ?AanvullingInkomen
    {
        return $this->aanvullingInkomen;
    }

    /**
     * @param AanvullingInkomen $aanvullingInkomen
     * @return Verhuurder
     */
    public function setAanvullingInkomen(?AanvullingInkomen $aanvullingInkomen): Verhuurder
    {
        $this->aanvullingInkomen = $aanvullingInkomen;
        return $this;
    }




}
