<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use OdpBundle\Exception\OdpException;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Verhuurder extends Deelnemer
{
    /**
     * @var ArrayCollection|Huuraanbod[]
     *
     * @ORM\OneToMany(targetEntity="Huuraanbod", mappedBy="verhuurder", cascade={"persist"})
     */
    private $huuraanbiedingen;

    /**
     * @var Woningbouwcorporatie
     *
     * @ORM\ManyToOne(targetEntity="Woningbouwcorporatie", inversedBy="verhuurders")
     */
    private $woningbouwcorporatie;

    /**
     * @var string
     *
     * @ORM\Column(name="woningbouwcorporatie_toelichting", nullable=true)
     */
    private $woningbouwcorporatieToelichting;

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
        if ($this->getActiefHuuraanbod()) {
            throw new OdpException('Een verhuurder kan slechts één actief huuraanbod tegelijk hebben');
        }

        $this->huuraanbiedingen[] = $huuraanbod;
        $huuraanbod->setVerhuurder($this);

        return $this;
    }

    public function getActiefHuuraanbod()
    {
        foreach ($this->huuraanbiedingen as $huuraanbod) {
            if ($huuraanbod->isActief()) {
                return $huuraanbod;
            }
        }
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
}
