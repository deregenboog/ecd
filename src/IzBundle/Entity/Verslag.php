<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use ErOpUitBundle\Form\KlantCloseType;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_verslagen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string", length=15)
 * @ORM\DiscriminatorMap({
 *     "verslag" = "Verslag",
 *     "tussenevaluatie" = "Tussenevaluatie",
 *     "eindevaluatie" = "Eindevaluatie"
 * })
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
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $opmerking;

    /**
     * @var IzDeelnemer
     * @ORM\ManyToOne(targetEntity="IzDeelnemer", inversedBy="verslagen")
     * @ORM\JoinColumn(name="iz_deelnemer_id")
     * @Gedmo\Versioned
     */
    protected $izDeelnemer;

    /**
     * @var Hulp
     * @ORM\ManyToOne(targetEntity="Hulp", inversedBy="verslagen")
     * @ORM\JoinColumn(name="iz_koppeling_id")
     * @Gedmo\Versioned
     */
    protected $koppeling;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $medewerker;
    
    public function getId()
    {
        return $this->id;
    }

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
        return $this->opmerking;
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;

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
