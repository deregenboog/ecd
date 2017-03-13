<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use OdpBundle\Exception\OdpException;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
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
     * @var Huurderafsluiting
     *
     * @ORM\ManyToOne(targetEntity="Huurderafsluiting", cascade={"persist"})
     */
    private $afsluiting;

    public function __construct()
    {
        parent::__construct();

        $this->huurverzoeken = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->huurverzoeken)
            && 0 === count($this->verslagen);
    }

    public function getHuurverzoeken()
    {
        return $this->huurverzoeken;
    }

    public function addHuurverzoek(Huurverzoek $huurverzoek)
    {
        if ($this->getActiefHuurverzoek()) {
            throw new OdpException('Een huurder kan slechts één actief huurverzoek tegelijk hebben');
        }

        $this->huurverzoeken[] = $huurverzoek;
        $huurverzoek->setHuurder($this);

        return $this;
    }

    public function getActiefHuurverzoek()
    {
        foreach ($this->huurverzoeken as $huurverzoek) {
            if ($huurverzoek->isActief()) {
                return $huurverzoek;
            }
        }
    }

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(Huurderafsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }
}
