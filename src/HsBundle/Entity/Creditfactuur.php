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
        if(is_null($this->opmerking)) return "";
        return mb_convert_encoding($this->opmerking, 'ISO-8859-1','UTF-8');
    }

    public function setOpmerking($opmerking = "")
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');
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
