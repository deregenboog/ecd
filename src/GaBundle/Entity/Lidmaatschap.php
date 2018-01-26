<?php

namespace GaBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="ga_lidmaatschappen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Lidmaatschap
{
    use IdentifiableTrait, TimestampableTrait;

    /**
     * @var Groep
     *
     * @ORM\ManyToOne(targetEntity="Groep", inversedBy="lidmaatschappen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $groep;

    /**
     * @var Dossier
     *
     * @ORM\ManyToOne(targetEntity="Dossier", inversedBy="lidmaatschappen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $dossier;

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
     * @ORM\ManyToOne(targetEntity="LidmaatschapAfsluitreden")
     * @ORM\JoinColumn(name="groepsactiviteiten_reden_id", nullable=true)
     * @Gedmo\Versioned
     */
    protected $afsluitreden;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $communicatieEmail = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $communicatieTelefoon = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $communicatiePost = true;

    public function __construct(Groep $groep = null, Dossier $dossier = null)
    {
        $this->startdatum = new \DateTime('today');
        $this->groep = $groep;
        $this->dossier = $dossier;
    }

    public function getGroep()
    {
        return $this->groep;
    }

    public function setGroep(Groep $groep)
    {
        $this->groep = $groep;

        return $this;
    }

    public function getDossier()
    {
        return $this->dossier;
    }

    public function setDossier(Dossier $dossier)
    {
        $this->dossier = $dossier;

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

    public function getAfsluitreden()
    {
        return $this->afsluitreden;
    }

    public function setAfsluitreden(LidmaatschapAfsluitreden $afsluitreden)
    {
        $this->afsluitreden = $afsluitreden;

        return $this;
    }

    public function close()
    {
        $this->einddatum = new \DateTime();

        return $this;
    }

    public function reopen()
    {
        $this->einddatum = null;
        $this->afsluitreden = null;

        return $this;
    }

    public function isActief()
    {
        $today = new \DateTime('today');

        return $this->startdatum <= $today
            && (!$this->einddatum || $this->einddatum < $today);
    }
}
