<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 *
 * @Gedmo\Loggable
 */
class Creditfactuur extends Factuur
{
    /**
     * @ORM\Column(type="text")
     *
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
        return utf8_decode($this->opmerking);
    }

    public function setOpmerking($opmerking = null)
    {
        $this->opmerking = utf8_encode($opmerking);

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

    public function getDatum(): \DateTime
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum): void
    {
        $this->datum = $datum;
    }
}
