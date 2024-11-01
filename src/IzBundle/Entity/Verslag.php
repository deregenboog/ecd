<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="iz_verslagen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 *
 * @ORM\DiscriminatorColumn(name="discr", type="string", length=15)
 *
 * @ORM\DiscriminatorMap({
 *     "verslag" = "Verslag",
 *     "tussenevaluatie" = "Tussenevaluatie",
 *     "eindevaluatie" = "Eindevaluatie"
 * })
 *
 * @Gedmo\Loggable
 */
class Verslag
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $opmerking;

    /**
     * @var IzDeelnemer
     *
     * @ORM\ManyToOne(targetEntity="IzDeelnemer", inversedBy="verslagen")
     *
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     *
     * @Gedmo\Versioned
     */
    protected $izDeelnemer;

    /**
     * @var Hulp
     *
     * @ORM\ManyToOne(targetEntity="Hulp", inversedBy="verslagen")
     *
     * @ORM\JoinColumn(name="iz_koppeling_id")
     *
     * @Gedmo\Versioned
     */
    protected $koppeling;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function getIzDeelnemer()
    {
        return $this->izDeelnemer;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function setIzDeelnemer(IzDeelnemer $izDeelnemer)
    {
        $this->izDeelnemer = $izDeelnemer;

        return $this;
    }

    public function getOpmerking()
    {
        return mb_convert_encoding($this->opmerking, 'ISO-8859-1','UTF-8');
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');
        return $this;
    }

    public function getKoppeling()
    {
        return $this->koppeling;
    }

    public function setKoppeling(Hulp $koppeling)
    {
        $this->koppeling = $koppeling;

        return $this;
    }
}
