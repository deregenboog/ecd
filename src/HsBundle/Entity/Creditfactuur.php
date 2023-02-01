<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @Gedmo\Loggable
 */
class Creditfactuur extends Factuur
{
    /**
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $opmerking;

    public function isDeletable()
    {
        return true;
    }

    public function isEmpty()
    {
        return false;
    }

    public function getOpmerking()
    {
        return $this->opmerking;
    }

    public function setOpmerking($opmerking = null)
    {
        $this->opmerking = $opmerking;

        return $this;
    }

    public function setNummer($nummer)
    {
        $this->nummer = $nummer;

        return $this;
    }

    public function setBetreft($betreft)
    {
        $this->betreft = $betreft;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatum(): \DateTime
    {
        return $this->datum;
    }

    /**
     * @param \DateTime $datum
     */
    public function setDatum(\DateTime $datum): void
    {
        $this->datum = $datum;
    }
}
