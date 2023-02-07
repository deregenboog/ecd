<?php

namespace GaBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="ga_verslagen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verslag
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Dossier", inversedBy="verslagen")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $opmerking;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * @param mixed $dossier
     */
    public function setDossier($dossier)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    /**
     * @param mixed $medewerker
     */
    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpmerking()
    {
        return utf8_decode($this->opmerking);
    }

    /**
     * @param mixed $opmerking
     */
    public function setOpmerking($opmerking)
    {
        $this->opmerking = utf8_encode($opmerking);

        return $this;
    }
}
