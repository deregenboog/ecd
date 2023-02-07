<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimeframeableTrait
{
    /**
     * @ORM\Column(name="datum_van", type="date")
     * @Gedmo\Versioned
     */
    protected $datumVan;

    /**
     * @ORM\Column(name="datum_tot", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $datumTot;

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
