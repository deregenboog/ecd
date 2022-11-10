<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class BinnenViaOptieKlant extends BinnenVia
{
    /**
     * @var Aanmelding[] $aanmeldingen
     * @ORM\OneToMany(targetEntity="MwBundle\Entity\Aanmelding", mappedBy="binnenViaOptieKlant" )
     */
    protected $aanmeldingen;

    /**
     * @var Klant
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Klant", mappedBy="mwBinnenViaOptieKlant")
     */
    protected $klant;


}
