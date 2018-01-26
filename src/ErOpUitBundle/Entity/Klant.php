<?php

namespace ErOpUitBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NotDeletableTrait;
use Doctrine\ORM\Mapping as ORM;
use GaBundle\Entity\LidmaatschapAfsluitreden;

/**
 * @ORM\Entity
 * @ORM\Table(name="eropuit_klanten")
 */
class Klant
{
    use IdentifiableTrait, NotDeletableTrait;

    /**
     * @var AppKlant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $klant;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $inschrijfdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
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
        return (string) $this->klant;
    }

    public function getKlant()
    {
        return $this->klant;
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
