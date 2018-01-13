<?php

namespace PfoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="pfo_groepen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Groep
{
    use IdentifiableTrait, NameableTrait, TimestampableTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @return \DateTime
     */
    public function getStartdatum()
    {
        return $this->startdatum;
    }

    /**
     * @param \DateTime $afsluitdatum
     */
    public function setStartdatum(\DateTime $startdatum)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEinddatum()
    {
        return $this->einddatum;
    }

    /**
     * @param \DateTime $einddatum
     */
    public function setEinddatum(\DateTime $einddatum)
    {
        $this->einddatum = $einddatum;

        return $this;
    }
}
