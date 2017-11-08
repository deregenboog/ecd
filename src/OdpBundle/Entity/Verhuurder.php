<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verhuurder extends Deelnemer
{
    /**
     * @var ArrayCollection|Huuraanbod[]
     *
     * @ORM\OneToMany(targetEntity="Huuraanbod", mappedBy="verhuurder", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC", "id" = "DESC"})
     */
    private $huuraanbiedingen;

    /**
     * @var Woningbouwcorporatie
     *
     * @ORM\ManyToOne(targetEntity="Woningbouwcorporatie", inversedBy="verhuurders")
     * @Gedmo\Versioned
     */
    private $woningbouwcorporatie;

    /**
     * @var string
     *
     * @ORM\Column(name="woningbouwcorporatie_toelichting", nullable=true)
     * @Gedmo\Versioned
     */
    private $woningbouwcorporatieToelichting;

    /**
     * "Kleine schuld, grote winst"
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $ksgw = false;

    public function __construct()
    {
        parent::__construct();

        $this->huuraanbiedingen = new ArrayCollection();
    }

    public function isActief()
    {
        return $this->afsluiting === null;
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

        usort($huurovereenkomsten, function($huurovereenkomst1, $huurovereenkomst2) {
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

    public function getWoningbouwcorporatie()
    {
        return $this->woningbouwcorporatie;
    }

    public function setWoningbouwcorporatie(Woningbouwcorporatie $woningbouwcorporatie)
    {
        $this->woningbouwcorporatie = $woningbouwcorporatie;

        return $this;
    }

    public function getWoningbouwcorporatieToelichting()
    {
        return $this->woningbouwcorporatieToelichting;
    }

    public function setWoningbouwcorporatieToelichting($woningbouwcorporatieToelichting)
    {
        $this->woningbouwcorporatieToelichting = $woningbouwcorporatieToelichting;

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
}
