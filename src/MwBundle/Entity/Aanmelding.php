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
     * @ORM\ManyToOne(targetEntity="MwBundle\Entity\BinnenViaOptieKlant", inversedBy="Aanmelding", )
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $binnenViaOptieKlant;


    public function __construct(Klant $klant, Medewerker $medewerker = null)
    {
        //

        parent::__construct($klant, $medewerker);
    }

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
        $this->binnenViaOptieKlant = $event->getEntityManager()->getReference('MwBundle:BinnenViaOptieKlant', 1);
    }
}
