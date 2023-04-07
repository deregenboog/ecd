<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use MwBundle\Service\BinnenViaKlantDao;
use Doctrine\ORM\Mapping\PrePersist;

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
     * @ORM\ManyToOne(targetEntity="BinnenViaOptieKlant")
     * @ORM\JoinColumn(nullable=false, options={"default": 0})
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


    /**
     * @PrePersist
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $event
     */
    public function onPrePersist(LifecycleEventArgs $event)
    {
        $this->created = $this->modified = new \DateTime();
        if (false === empty($this->binnenViaOptieKlant)) {
            return;
        }
        $this->binnenViaOptieKlant = $event->getEntityManager()->getReference('MwBundle:BinnenViaOptieKlant', 0);
    }
}
