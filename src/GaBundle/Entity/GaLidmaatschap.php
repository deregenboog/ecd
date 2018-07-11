<?php

namespace GaBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\MappedSuperclass
 * @Gedmo\Loggable
 */
abstract class GaLidmaatschap
{
    use IdentifiableTrait, TimestampableTrait;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $einddatum;

    /**
     * @ORM\ManyToOne(targetEntity="GaReden")
     * @ORM\JoinColumn(name="groepsactiviteiten_reden_id", nullable=true)
     * @Gedmo\Versioned
     */
    protected $gaReden;

    /**
     * @ORM\Column(name="communicatie_email", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $communicatieEmail;

    /**
     * @ORM\Column(name="communicatie_telefoon", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $communicatieTelefoon;

    /**
     * @ORM\Column(name="communicatie_post", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $communicatiePost;

    public function __construct(GaGroep $gaGroep = null)
    {
        $this->startdatum = new \DateTime('today');
        $this->gaGroep = $gaGroep;
    }

    public function getGroep()
    {
        return $this->gaGroep;
    }

    public function setGroep(GaGroep $gaGroep)
    {
        $this->gaGroep = $gaGroep;

        return $this;
    }

    public function isCommunicatieEmail()
    {
        return $this->communicatieEmail;
    }

    public function setCommunicatieEmail($communicatie)
    {
        $this->communicatieEmail = (bool) $communicatie;

        return $this;
    }

    public function isCommunicatiePost()
    {
        return $this->communicatiePost;
    }

    public function setCommunicatiePost($communicatie)
    {
        $this->communicatiePost = (bool) $communicatie;

        return $this;
    }

    public function isCommunicatieTelefoon()
    {
        return $this->communicatieTelefoon;
    }

    public function setCommunicatieTelefoon($communicatie)
    {
        $this->communicatieTelefoon = (bool) $communicatie;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $datum)
    {
        $this->startdatum = $datum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(\DateTime $datum)
    {
        $this->einddatum = $datum;

        return $this;
    }
}
