<?php

namespace GaBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="ga_verslagen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Verslag
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Dossier", inversedBy="verslagen")
     *
     * @Gedmo\Versioned
     */
    protected $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="Activiteit", inversedBy="verslagen")
     *
     * @Gedmo\Versioned
     */
    protected $activiteit;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $opmerking;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function getDossier()
    {
        return $this->dossier;
    }

    public function setDossier($dossier)
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getActiviteit(): ?Activiteit
    {
        return $this->activiteit;
    }

    public function setActiviteit(Activiteit $activiteit): self
    {
        $this->activiteit = $activiteit;

        return $this;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getOpmerking(): string
    {
        if(is_null($this->opmerking)) return "";
        return mb_convert_encoding($this->opmerking ?? "", 'ISO-8859-1','UTF-8');
    }

    public function setOpmerking(?string $opmerking = "")
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');

        return $this;
    }
}
