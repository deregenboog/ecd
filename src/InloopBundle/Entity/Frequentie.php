<?php

namespace InloopBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="verslavingsfrequenties")
 * @Gedmo\Loggable
 */
class Frequentie
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_van", type="date")
     * @Gedmo\Versioned
     */
    private $datumVan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_tot", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $datumTot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct()
    {
        $this->datumVan = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getDatumVan()
    {
        return $this->datumVan;
    }

    /**
     * @param \DateTime $datumVan
     */
    public function setDatumVan(\DateTime $datumVan)
    {
        $this->datumVan = $datumVan;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatumTot()
    {
        return $this->datumTot;
    }

    /**
     * @param \DateTime $datumTot
     */
    public function setDatumTot(\DateTime $datumTot = null)
    {
        $this->datumTot = $datumTot;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }
}
