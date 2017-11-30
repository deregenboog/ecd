<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use HsBundle\Exception\HsException;
use HsBundle\Exception\InvoiceLockedException;
use Symfony\Component\Security\Core\Exception\LockedException;
use HsBundle\Exception\InvoiceNotLockedException;

/**
 * @ORM\Entity()
 * @Gedmo\Loggable
 */
class Creditfactuur extends Factuur
{
    /**
     * @ORM\Column(type="text", nullable=false)
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
}
