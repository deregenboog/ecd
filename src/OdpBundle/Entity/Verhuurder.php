<?php

namespace OdpBundle\Entity;

use AppBundle\Entity\Klant;
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
     * @ORM\ManyToOne(targetEntity="OdpBundle\Entity\Project", inversedBy="verhuurders", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $project;

    public function __construct(Klant $klant = null)
    {
        if(null !== $klant){
            $this->klant = $klant;
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


}
