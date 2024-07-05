<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="GaBundle\Repository\DeelnemerRepository")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Klantdossier extends Dossier
{
    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    private $klant;

    public function __construct(?Klant $klant = null)
    {
        $this->klant = $klant;
        parent::__construct();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->klant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }
}
