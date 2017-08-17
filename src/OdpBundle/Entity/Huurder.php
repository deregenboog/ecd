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
class Huurder extends Deelnemer
{
    /**
     * @var ArrayCollection|Huurverzoek[]
     *
     * @ORM\OneToMany(targetEntity="Huurverzoek", mappedBy="huurder", cascade={"persist"})
     */
    private $huurverzoeken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $startdatumAutomatischeIncasso;

    public function __construct()
    {
        parent::__construct();

        $this->huurverzoeken = new ArrayCollection();
    }

    public function isActief()
    {
        return $this->afsluiting === null;
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

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(HuurderAfsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }

    public function getStartdatumAutomatischeIncasso()
    {
        return $this->startdatumAutomatischeIncasso;
    }

    public function setStartdatumAutomatischeIncasso(\DateTime $startdatumAutomatischeIncasso = null)
    {
        $this->startdatumAutomatischeIncasso = $startdatumAutomatischeIncasso;

        return $this;
    }
}
