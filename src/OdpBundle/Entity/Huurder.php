<?php

namespace OdpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Huurder extends Deelnemer
{
    /**
     * @var HuurderAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="HuurderAfsluiting", inversedBy="huurders", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $afsluiting;

    /**
     * @var ArrayCollection|Huurverzoek[]
     *
     * @ORM\OneToMany(targetEntity="Huurverzoek", mappedBy="huurder", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC", "id" = "DESC"})
     */
    private $huurverzoeken;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $automatischeIncasso;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $inschrijvingWoningnet;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $waPolis;

    /**
     * @var Huurbudget
     * @ORM\ManyToOne(targetEntity="Huurbudget",inversedBy="huurder",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"maxValue" = "ASC"})
     */
    private $huurbudget;

    /**
     * @var DuurThuisloos
     * @ORM\ManyToOne(targetEntity="OdpBundle\Entity\DuurThuisloos",inversedBy="huurder",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"maxValue" = "ASC"})
     */
    private $duurThuisloos;

    /**
     * @var Werk
     * @ORM\ManyToOne(targetEntity="OdpBundle\Entity\Werk",inversedBy="huurder",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $werk;


    public function __construct()
    {
        parent::__construct();

        $this->huurverzoeken = new ArrayCollection();
    }

    public function isActief()
    {
        return null === $this->afsluiting;
    }

    public function isClosable()
    {
        $today = new \DateTime('today');

        $actieveHuurverzoeken = array_filter($this->huurverzoeken->toArray(), function (Huurverzoek $huurverzoek) use ($today) {
            if ($huurverzoek->getAfsluitdatum() && $huurverzoek->getAfsluitdatum() <= $today) {
                return false;
            }
            $huurovereenkomst = $huurverzoek->getHuurovereenkomst();
            if ($huurovereenkomst && $huurovereenkomst->getAfsluitdatum() && $huurovereenkomst->getAfsluitdatum() <= $today) {
                return false;
            }

            return true;
        });

        return 0 === count($actieveHuurverzoeken);
    }

    public function isDeletable()
    {
        return 0 === count($this->huurverzoeken)
            && 0 === count($this->documenten)
            && 0 === count($this->verslagen);
    }

    public function getHuurverzoeken()
    {
        return $this->huurverzoeken;
    }

    public function addHuurverzoek(Huurverzoek $huurverzoek)
    {
        $this->huurverzoeken[] = $huurverzoek;
        $huurverzoek->setHuurder($this);

        return $this;
    }

    public function getHuurovereenkomsten()
    {
        $huurovereenkomsten = [];
        foreach ($this->huurverzoeken as $huurverzoek) {
            if ($huurverzoek->getHuurovereenkomst()) {
                $huurovereenkomsten[] = $huurverzoek->getHuurovereenkomst();
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

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(HuurderAfsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }

    public function isAutomatischeIncasso()
    {
        return $this->automatischeIncasso;
    }

    public function setAutomatischeIncasso($automatischeIncasso)
    {
        $this->automatischeIncasso = (bool) $automatischeIncasso;

        return $this;
    }

    public function isInschrijvingWoningnet()
    {
        return $this->inschrijvingWoningnet;
    }

    public function setInschrijvingWoningnet($inschrijvingWoningnet)
    {
        $this->inschrijvingWoningnet = (bool) $inschrijvingWoningnet;

        return $this;
    }

    public function isWaPolis()
    {
        return $this->waPolis;
    }

    public function setWaPolis($waPolis)
    {
        $this->waPolis = (bool) $waPolis;

        return $this;
    }

    /**
     * @return Huurbudget
     */
    public function getHuurbudget(): ?Huurbudget
    {
        return $this->huurbudget;
    }

    /**
     * @param Huurbudget $huurbudget
     */
    public function setHuurbudget(Huurbudget $huurbudget): void
    {
        $this->huurbudget = $huurbudget;
    }

    /**
     * @return DuurThuisloos
     */
    public function getDuurThuisloos(): ?DuurThuisloos
    {
        return $this->duurThuisloos;
    }

    /**
     * @param DuurThuisloos $duurThuisloos
     */
    public function setDuurThuisloos(DuurThuisloos $duurThuisloos): void
    {
        $this->duurThuisloos = $duurThuisloos;
    }

    /**
     * @return Werk
     */
    public function getWerk(): ?Werk
    {
        return $this->werk;
    }

    /**
     * @param Werk $werk
     */
    public function setWerk(Werk $werk): void
    {
        $this->werk = $werk;
    }


}
