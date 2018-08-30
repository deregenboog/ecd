<?php

namespace ErOpUitBundle\Entity;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NotDeletableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="eropuit_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Vrijwilliger
{
    use IdentifiableTrait, NotDeletableTrait;

    /**
     * @var AppVrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $vrijwilliger;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $inschrijfdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $uitschrijfdatum;

    /**
     * @var LidmaatschapAfsluitreden
     *
     * @ORM\ManyToOne(targetEntity="GaBundle\Entity\LidmaatschapAfsluitreden")
     */
    private $uitschrijfreden;

    /**
     * @ORM\Column(name="communicatie_email", type="boolean", nullable=true)
     */
    private $communicatieEmail = true;

    /**
     * @ORM\Column(name="communicatie_telefoon", type="boolean", nullable=true)
     */
    private $communicatieTelefoon = true;

    /**
     * @ORM\Column(name="communicatie_post", type="boolean", nullable=true)
     */
    private $communicatiePost = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $modified;

    public function __toString()
    {
        return (string) $this->vrijwilliger;
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    public function getInschrijfdatum()
    {
        return $this->inschrijfdatum;
    }

    public function getUitschrijfdatum()
    {
        return $this->uitschrijfdatum;
    }

    public function getUitschrijfreden()
    {
        return $this->uitschrijfreden;
    }

    public function isCommunicatieEmail()
    {
        return $this->communicatieEmail;
    }

    public function isCommunicatiePost()
    {
        return $this->communicatiePost;
    }

    public function isCommunicatieTelefoon()
    {
        return $this->communicatieTelefoon;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }
}
