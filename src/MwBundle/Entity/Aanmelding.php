<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Aanmelding extends MwDossierStatus
{
    public function __toString()
    {
        return sprintf(
            'Aanmelding op %s',
            $this->datum->format('d-m-Y')
        );
    }
    /**
     * @var BinnenViaOptieKlant
     *
     * @ORM\ManyToOne(targetEntity="MwBundle\Entity\BinnenViaOptieKlant", inversedBy="Aanmelding")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $binnenViaOptieKlant;

    /**
     * @return BinnenViaOptieKlant
     */
    public function getBinnenViaOptieKlant(): ?BinnenViaOptieKlant
    {
        return $this->binnenViaOptieKlant;
    }

    /**
     * @param BinnenViaOptieKlant $binnenViaOptieKlant
     * @return Aanmelding
     */
    public function setBinnenViaOptieKlant(BinnenViaOptieKlant $binnenViaOptieKlant): Aanmelding
    {
        $this->binnenViaOptieKlant = $binnenViaOptieKlant;
        return $this;
    }


}
