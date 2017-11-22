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

    public function __construct()
    {
        parent::__construct();

        $this->huurverzoeken = new ArrayCollection();
    }

    public function isActief()
    {
        return null === $this->afsluiting;
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
}
