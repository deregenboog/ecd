<?php

namespace ScipBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\MedewerkerSubjectInterface;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="scip_verslagen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Verslag implements MedewerkerSubjectInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $tekst;

    /**
     * @var Deelnemer
     *
     * @ORM\ManyToOne(targetEntity="Deelnemer", inversedBy="verslagen", fetch="EAGER")
     *
     * @Gedmo\Versioned
     */
    protected $deelnemer;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    protected $datum;

    /**
     * @var bool
     *
     * @ORM\Column()
     *
     * @Gedmo\Versioned
     */
    protected $isEvaluatie = false;

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

    public function __construct()
    {
        $this->datum = new \DateTime();
    }

    public function getDeelnemer()
    {
        return $this->deelnemer;
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

    public function setDeelnemer(Deelnemer $deelnemer)
    {
        $this->deelnemer = $deelnemer;

        return $this;
    }

    public function getTekst()
    {
        return $this->tekst;
    }

    public function setTekst($tekst)
    {
        $this->tekst = $tekst;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function isEvaluatie(): bool
    {
        return $this->isEvaluatie;
    }

    public function setIsEvaluatie(bool $isEvaluatie): void
    {
        $this->isEvaluatie = $isEvaluatie;
    }
}
