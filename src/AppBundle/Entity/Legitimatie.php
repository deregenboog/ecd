<?php

namespace AppBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="legitimaties")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Legitimatie
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="naam", nullable=false)
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(name="datum_van", type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $datumVan;

    /**
     * @ORM\Column(name="datum_tot", type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $datumTot;

    public function __toString()
    {
        return $this->naam;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->land;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    public function getDatumVan()
    {
        return $this->datumVan;
    }

    public function setDatumVan(\DateTime $datumVan)
    {
        $this->datumVan = $datumVan;

        return $this;
    }

    public function getDatumTot()
    {
        return $this->datumTot;
    }

    public function setDatumTot(\DateTime $datumTot)
    {
        $this->datumTot = $datumTot;

        return $this;
    }
}
